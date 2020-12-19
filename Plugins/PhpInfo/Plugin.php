<?php


namespace Plugins\PhpInfo;
use Kernel\Interfaces\IPlugin;
use Kernel\Kernel;

class Plugin implements IPlugin
{

    public function install(): array
    {
        return [
            [
                "uri" => "/admin/phpinfo",
                "controller" => "MainController",
                "function" => "index",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ]
        ];
    }

    public function getConfig(): array
    {
        return [
            "namespace" => "PhpInfo",
            "fullName" => "PHP Info",
            "defaultUri" => "/admin/phpinfo"
        ];
    }

    public function postInstall($insert_id): void
    {
        // TODO: Implement postInstall() method.
    }

    public function cleanup(int $plugin_id): void
    {
        // TODO: Implement cleanup() method.
    }
}

return new Plugin();