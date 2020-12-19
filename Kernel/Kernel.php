<?php

namespace Kernel;
use Kernel\Db;
use Kernel\Display;
use Kernel\Router;
use Kernel\Config;
use Middleware\CheckIsInstalled;
use Middleware\IMiddleware;
use PDO;

class Kernel {
    public static PDO $db;

    public static function run() {
        require_once "./routes.php";

        $isInstalled = new CheckIsInstalled();
        $isInstalled->handle();

        $router = new Router();
        $router->parseRoutes();

    }

    public static function tryWriteConfigFile($host, $dbname, $user, $password) {
        try {
            $json = [
                "db_host" => $host,
                "db_name" => $dbname,
                "db_username" => $user,
                "db_password" => $password
            ];

            file_put_contents('config.json', json_encode($json));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $host
     * @param $dbname
     * @param $dbuser
     * @param $dbpwd
     */
    public static function tryConnect($host, $dbname, $dbuser, $dbpwd): bool
    {
        try {
            $db = new Db($host, $dbname, $dbuser, $dbpwd);
            if (!$db) {
                return false;
            }
            Kernel::$db = $db->connection;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function checkRoutesTableExists(): bool {

        try {
            $stmt = Kernel::$db->prepare("SELECT count(*) FROM routes");
            $stmt->execute();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function checkAdminUserExists(): bool
    {
        try {
            $sql = "SELECT * FROM users LIMIT 1";
            $stmt = Kernel::$db->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $exception) {
            return false;
        }
    }

}