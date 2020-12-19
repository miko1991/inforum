<?php


namespace Plugins\Clubs;
use Kernel\Interfaces\IPlugin;
use Kernel\Kernel;
use Kernel\ServiceProviders\PluginServiceProvider;


class Plugin implements IPlugin
{

    public function install(): array
    {
        PluginServiceProvider::installTables("./Plugins/Clubs/create_clubs_table.sql");

        return [
            [
                "uri" => "/admin/clubs",
                "controller" => "MainController",
                "function" => "index",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/clubs/create",
                "controller" => "MainController",
                "function" => "create",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/clubs/create",
                "controller" => "MainController",
                "function" => "createProcess",
                "method" => "post",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/clubs/([0-9]+)/delete",
                "controller" => "MainController",
                "function" => "deleteClub",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ]
        ];
    }

    public function getConfig(): array
    {
        return [
            "namespace" => "Clubs",
            "fullName" => "Clubs",
            "defaultUri" => "/admin/clubs"
        ];
    }

    public function postInstall($insert_id): void
    {
        // TODO: Implement postInstall() method.
    }

    public function cleanup($plugin_id): void
    {

    }
}

return new Plugin();

