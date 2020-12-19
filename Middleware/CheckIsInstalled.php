<?php


namespace Middleware;
use Kernel\Config;
use Kernel\Display;
use Kernel\Kernel;
use Kernel\Router;
use Middleware\IMiddleware;

class CheckIsInstalled implements IMiddleware
{
    public function handle() {
        if (!file_exists("./config.json")) {
            if ($_SERVER["REQUEST_URI"] != "/admin/install"
                && $_SERVER["REQUEST_URI"] != "/admin/install/stepAccount"
                && $_SERVER["REQUEST_URI"] != "/admin/install/installTables"
                && $_SERVER["REQUEST_URI"] != "/admin/install/createAdminUser") {
                header("Location: /admin/install");
                exit();
            }
        } else {
            $jsonFile = file_get_contents("./config.json");
            $parsedJson = json_decode($jsonFile);

            $db_host = $parsedJson->db_host;
            $db_name = $parsedJson->db_name;
            $db_username = $parsedJson->db_username;
            $db_password = $parsedJson->db_password;

            $connected = Kernel::tryConnect($db_host, $db_name, $db_username, $db_password);

            if (!$connected) {
                if ($_SERVER["REQUEST_URI"] != "/admin/install") {
                    Display::flash("error", "Could not connect to database.");
                    Display::with("form_values", $_POST);
                    header("Location: /admin/install");
                    exit();
                }
            } elseif (!Kernel::checkRoutesTableExists()) {
                if (
                    $_SERVER["REQUEST_URI"] != "/admin/install/stepAccount"
                    && $_SERVER["REQUEST_URI"] != "/admin/install/installTables"
                    && $_SERVER["REQUEST_URI"] != "/admin/install/createAdminUser") {
                    header("Location: /admin/install/installTables");
                    exit();
                }

            }
        }
    }
}