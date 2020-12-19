<?php


namespace Kernel\ServiceProviders;


use Helpers\Helpers;
use Kernel\Interfaces\IPlugin;
use Kernel\Kernel;

class PluginServiceProvider
{
    public static function install(array $config, array $routes): int {
        $stmt = Kernel::$db->prepare("INSERT INTO plugins (namespace, default_uri) VALUES (?, ?)");
        $stmt->execute([$config["namespace"], $config["defaultUri"]]);
        $plugin_id = Kernel::$db->lastInsertId();

        foreach ($routes as $route) {
            $stmt = Kernel::$db->prepare("INSERT INTO plugin_routes (plugin_id, uri, controller, function, method, middlewares) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$plugin_id, $route["uri"], $route["controller"], $route["function"], $route["method"], $route["middlewares"]]);
        }

        return $plugin_id;
    }

    public static function loadPlugin($dir): ?IPlugin {
        $filename = "./Plugins/".$dir."/Plugin.php";
        if (!file_exists($filename)) {
            return null;
        }

        return require_once $filename;
    }

    public static function loadPluginFromDb($id): IPlugin {
        $stmt = Kernel::$db->prepare("SELECT * FROM plugins WHERE id = ?");
        $stmt->execute([$id]);
        $plugin = $stmt->fetch(\PDO::FETCH_OBJ);

        return require_once "./Plugins/".$plugin->namespace."/Plugin.php";
    }

    public static function deactivatePlugin($id) {
        $stmt = Kernel::$db->prepare("SELECT * FROM plugins WHERE id = ?");
        $stmt->execute([$id]);
        $plugin = $stmt->fetch(\PDO::FETCH_OBJ);

        $pluginFunc = self::loadPluginFromDb($plugin->id);

        $stmt = Kernel::$db->prepare("DELETE FROM plugins WHERE id = ?");
        $stmt->execute([$id]);

        $stmt = Kernel::$db->prepare("DELETE FROM plugin_routes WHERE plugin_id = ?");
        $stmt->execute([$plugin->id]);

        $pluginFunc->cleanup($plugin->id);
    }

    public static function deletePlugin($dir): void {
        Helpers::deleteDirectory($dir);
    }

    public static function uploadPlugin(): string {
        $target_dir = "Plugins/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        // Check if image file is a actual image or fake image
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        return $target_file;
    }

    public static function downloadPlugin($dir, $path): string {
        $target_file = "./Plugins/".$dir.".zip";

        file_put_contents($target_file,
            file_get_contents("http://localhost:5001/api".$path)
        );

        return $target_file;
    }

    public static function installWidget($plugin_id, $class_name, $title, $resource_id, $table_name): void {

    }

    public static function installTables($file): void {
        $sql = file_get_contents($file);
        $stmt = Kernel::$db->prepare($sql);
        $stmt->execute();
    }

    public static function removePluginZip($target_file): void {
        unlink($target_file);

    }

    public static function unzipPlugin($file): void {
        $zip = new \ZipArchive;
        $res = $zip->open($file);

        if ($res === TRUE) {
            // extract it to the path we determined above
            $zip->extractTo("Plugins/");
            $zip->close();
        }
    }
}