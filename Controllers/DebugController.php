<?php


namespace Controllers;


use Kernel\Kernel;

class DebugController
{
    public function reloadRoutes() {
        $stmt = Kernel::$db->prepare("DELETE FROM routes");
        $stmt->execute();

        $routes = require_once "./Db/Routes/routes.php";

        foreach ($routes as $route) {
            $stmt = Kernel::$db->prepare("INSERT INTO routes (uri, controller, function, method, middlewares) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$route["uri"], $route["controller"], $route["function"], $route["method"], $route["middlewares"]]);
        }

        header("Location: /");
    }

    public function reloadApplications() {
        $stmt = Kernel::$db->prepare("DELETE FROM applications");
        $stmt->execute();

        $apps = require_once "./Db/Applications/applications.php";

        foreach ($apps as $app) {
            $stmt = Kernel::$db->prepare("INSERT INTO applications (title, prop, locked, enabled) VALUES (?, ?, ?, ?)");
            $stmt->execute([$app["title"], $app["prop"], $app["locked"], $app["enabled"]]);
        }

        header("Location: /");
    }
}