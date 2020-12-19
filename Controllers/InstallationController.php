<?php

namespace Controllers;

use Kernel\Config;
use Kernel\Db;
use Kernel\Display;
use Kernel\Kernel;
use Kernel\Template;
use Helpers\Helpers;
use Models\Group;
use Models\PermissionSet;
use Models\Setting;
use Models\SettingGroup;
use Models\User;


class InstallationController
{
    private $connected;
    private $config;

    public function __construct()
    {
        $this->config = Config::load();

        if (!$this->config) return;

        $this->connected = Kernel::tryConnect(
            $this->config->db_host,
            $this->config->db_name,
            $this->config->db_username,
            $this->config->db_password
        );
    }

    public function index($errors = [])
    {
        $with = Display::with("form_values");
        $errors = Display::flash("errors");
        $error = Display::flash("error");
        Template::view("Installation.html", "./Views/", [
            "with" => $with,
            "errors" => $errors,
            "error" => $error
        ]);
        exit();
    }

    public function installTables()
    {
        if (!$this->connected) {
            $requiredFields = ["db_host" => "Database Host", "db_name" => "Database Name", "db_user" => "Database User"];
            $errors = [];

            foreach ($requiredFields as $field => $value) {
                if (!$_POST[$field]) {
                    $errors[$field] = $value . " is required";
                }
            }

            if (count($errors)) {
                Display::flash("errors", $errors);
                Display::with("form_values", $_POST);
                header("Location: /admin/install");
                exit();
            }

            $host = $_POST["db_host"];
            $dbname = $_POST["db_name"];
            $dbuser = $_POST["db_user"];
            $dbpwd = $_POST["db_password"];

            if (!Kernel::tryConnect($host, $dbname, $dbuser, $dbpwd)) {
                $this->flashConnectionError();
            }

            if (!Kernel::tryWriteConfigFile($host, $dbname, $dbuser, $dbpwd)) {
                $this->flashWriteConfigurationFileError();
            }
        }

        $sqls = $this->getSql();
        foreach ($sqls as $sql) {
            $stmt = Kernel::$db->prepare($sql);
            $stmt->execute();
        }

        $this->populateRoutes();
        $this->populateApplications();
        $this->populatePermissionSets();
        $this->populateGroups();
        $this->populateSettings();

        header("Location: /admin/install/stepAccount");
        exit();
    }

    private function populateSettings()
    {
        $datas = require_once "./Db/Settings/settings.php";

        foreach ($datas as $group) {
            $settingGroup = new SettingGroup();
            $settingGroup->title = $group["group_title"];
            $settingGroup->description = $group["group_description"];
            $settingGroup->save();

            foreach ($group["settings"] as $settingData) {
                $setting = new Setting();
                $setting->prop = $settingData["prop"];
                $setting->title = $settingData["title"];
                $setting->group_id = $settingGroup->id;
                $setting->save();
            }
        }
    }

    private function populatePermissionSets()
    {
        $datas = require_once "./Db/PermissionSets/permission_sets.php";

        foreach ($datas as $data) {
            $set = new PermissionSet();
            $set->can_access_acp = $data["can_access_acp"];
            $set->save();
        }
    }

    private function populateGroups()
    {
        $datas = require_once "./Db/Groups/groups.php";

        foreach ($datas as $data) {
            $group = new Group();
            $group->title = $data["title"];
            $group->is_root = $data["is_root"];
            $set = PermissionSet::where(["can_access_acp" => $data["is_root"] ? "1" : "0"])->first();
            $group->permission_set_id = $set->id;
            $group->save();
        }
    }

    private function populateApplications()
    {
        $apps = require_once "./Db/Applications/applications.php";

        foreach ($apps as $app) {
            $stmt = Kernel::$db->prepare("INSERT INTO applications (title, prop, locked, enabled) VALUES (?, ?, ?, ?)");
            $stmt->execute([$app["title"], $app["prop"], $app["locked"], $app["enabled"]]);
        }
    }

    private function populateRoutes()
    {
        $routes = require_once "./Db/Routes/routes.php";

        foreach ($routes as $route) {
            $stmt = Kernel::$db->prepare("INSERT INTO routes (uri, controller, function, method, middlewares) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$route["uri"], $route["controller"], $route["function"], $route["method"], $route["middlewares"]]);
        }
    }

    public function stepAccount()
    {
        if (!$this->config) {
            Display::flash("error", "Configuration file does not exist.");
            header("Location: /admin/install");
            exit();
        } else {
            if (!Kernel::tryConnect(
                $this->config->db_host,
                $this->config->db_name,
                $this->config->db_username,
                $this->config->db_password
            )) {
                $this->flashConnectionError();
            } elseif (!Kernel::checkRoutesTableExists()) {
                header("Location: /admin/install/installTables");
                exit();
            }
        }

        $with = Display::with("form_values");
        $errors = Display::flash("errors");
        $error = Display::flash("error");
        Template::view("InstallationStepAccount.html", "./Views/", [
            "with" => $with,
            "errors" => $errors,
            "error" => $error
        ]);
    }

    public function createAdminUser()
    {
        $requiredFields = ["display_name" => "Display Name", "email" => "Email", "password" => "Password", "password_again" => "Password Confirmation"];
        $errors = [];

        if ($_POST["password"] != $_POST["password_again"]) {
            $errors["password_again"] = "Passwords must match";
        }

        foreach ($requiredFields as $field => $value) {
            if (!$_POST[$field]) {
                $errors[$field] = $value . " is required";
            }
        }

        if (count($errors)) {
            Display::flash("errors", $errors);
            Display::with("form_values", $_POST);
            header("Location: /admin/install/stepAccount");
            exit();
        }

        $hash = Helpers::generateHash($_POST["password"]);
        //$hash = substr( $hash, 0, 60 );

        $rootAdminGroup = Group::where(["is_root" => 1])->first();
        $user = new User();
        $user->displayName = $_POST["display_name"];
        $user->email = $_POST["email"];
        $user->password = $hash;
        $user->group_id = $rootAdminGroup->id;
        $user->save();
        header("Location: /");
    }


    private function getSql()
    {
        $sqls = [];

        $dirContents = scandir("./Db/Migrations");
        foreach ($dirContents as $content) {
            if ($content == "." || $content == "..") continue;
            $sqls[] = file_get_contents("./Db/Migrations/" . $content);
        }

        return $sqls;
    }

    private function flashConnectionError()
    {
        Display::flash("error", "Could not connect to database.");
        Display::with("form_values", $_POST);
        header("Location: /admin/install");
        exit();
    }

    private function flashWriteConfigurationFileError(): void
    {
        Display::flash("error", "Error writing configuration file.");
        Display::with("form_values", $_POST);
        header("Location: /admin/install");
        exit();
    }
}
