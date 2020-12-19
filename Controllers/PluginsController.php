<?php


namespace Controllers;


use Kernel\Interfaces\IPlugin;
use Kernel\Kernel;
use Kernel\Plugin;
use Kernel\ServiceProviders\PluginServiceProvider;
use Kernel\Template;
use Helpers\Helpers;
use ZipArchive;

class PluginsController
{
    public function index(){
        $dir = scandir("./Plugins");
        $plugins = [];
        foreach ($dir as $content) {
            if ($content == "." || $content == ".." || $content == "__MACOSX" || $content == ".DS_Store") continue;
            $plugins[] = $content;
        }

        $parsedPlugins = [];
        foreach ($plugins as &$dir) {
            $config = [];
            $pluginModel = null;
            $error = false;

            $plugin = PluginServiceProvider::loadPlugin($dir);
            if (!$plugin instanceof IPlugin) {
                $error = true;
            } else {

                $config = $plugin->getConfig();

                $pluginModel = null;
                if (!$config["namespace"]) {
                    $error = true;
                } else {
                    $stmt = Kernel::$db->prepare("SELECT * FROM plugins WHERE namespace = ?");
                    $stmt->execute([$config["namespace"]]);
                    $pluginModel = $stmt->fetch(\PDO::FETCH_OBJ);
                }
            }



            $parsedPlugins[] = ["model" => $pluginModel, "dir" => $dir, "config" => $config, "error" => $error];
        }


        Template::view("AdminPluginBrowse.html", "./Views/", [
            "plugins" => $parsedPlugins
        ]);
    }

    public function install($dir) {
        $plugin = PluginServiceProvider::loadPlugin($dir);
        $routes = $plugin->install();
        $insertId = PluginServiceProvider::install($plugin->getConfig(), $routes);
        $plugin->postInstall($insertId);
        header("Location: /admin/plugins");
    }

    public function deactivatePlugin($id) {

        PluginServiceProvider::deactivatePlugin($id);
        header("Location: /admin/plugins");
    }

    public function deletePlugin($id) {
        $plugin = PluginServiceProvider::loadPluginFromDb($id);

        try {
            $dir = "./Plugins/".$plugin->getConfig()["namespace"];
            PluginServiceProvider::deletePlugin($dir);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            exit();
        }

        header("Location: /admin/plugins");
    }

    public function download() {
        $dir = $_POST["title"];
        $path = $_POST["path"];

        $target_file = PluginServiceProvider::downloadPlugin($dir, $path);
        PluginServiceProvider::unzipPlugin($target_file);
        PluginServiceProvider::removePluginZip($target_file);
    }

    public function addPlugin(){
        Template::view("AdminPluginAdd.html", "./Views/");
    }

    public function addPluginProcess() {
        $target_file = PluginServiceProvider::uploadPlugin();
        PluginServiceProvider::unzipPlugin($target_file);
        PluginServiceProvider::removePluginZip($target_file);

        header("Location: /admin/plugins");
    }
}