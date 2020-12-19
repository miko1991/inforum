<?php


namespace Controllers;


use Kernel\Authentication;
use Kernel\Kernel;
use Kernel\Model;
use Kernel\Response;
use Kernel\Template;
use Models\Application;
use Models\User;

class ApplicationsController
{
    public function toggle($id) {
        $app = Application::findById($id);
        $app->enabled = $app->enabled == 0 ? 1 : 0;
        $app->save();
        echo $app->enabled;
    }

    public function index() {
        Template::view("AdminApplicationsBrowse.html", "./Views/");
    }

    public function reorder() {
        $apps = json_decode($_POST["apps"]);

        foreach ($apps as $app) {
            $stmt = Kernel::$db->prepare("UPDATE `applications` SET `order` = ? WHERE `id` = ?");
            $stmt->execute([$app->order, $app->id]);
        }
    }

    public function browse() {
        $apps = Application::all(true);
        $user = User::with(["group"])->where(["id" => 1])->first();

        Response::json(["apps" => $apps, "user" => $user]);
    }
}