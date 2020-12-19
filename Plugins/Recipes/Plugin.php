<?php


namespace Plugins\Recipes;
use Kernel\Interfaces\IPlugin;
use Kernel\Kernel;
use Kernel\QueryBuilder;
use Kernel\ServiceProviders\PluginServiceProvider;

class Plugin implements IPlugin
{

    public function install(): array
    {
        PluginServiceProvider::installTables("./Plugins/Recipes/create_recipes_table.sql");

        return [
            [
                "uri" => "/admin/recipes",
                "controller" => "MainController",
                "function" => "index",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/recipes/getAll",
                "controller" => "MainController",
                "function" => "getAll",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/recipes/add",
                "controller" => "MainController",
                "function" => "create",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/recipes/add",
                "controller" => "MainController",
                "function" => "createProcess",
                "method" => "post",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ]
        ];
    }

    public function getConfig(): array
    {
        return [
            "namespace" => "Recipes",
            "fullName" => "Cool Recipes",
            "defaultUri" => "/admin/recipes"
        ];
    }

    public function postInstall($insert_id): void
    {
        $this->addRecipeBrowserWidget($insert_id);
    }

    private function addRecipeBrowserWidget($insert_id) {
        $qb = new QueryBuilder();
        $qb->insert("plugin_widgets", ["plugin_id" => $insert_id, "title" => "Recipe Browser", "class_name" => "RecipeBrowserWidget"])->exec();
    }

    public function cleanup(int $plugin_id): void
    {
        $qb = new QueryBuilder();
        $qb->delete("plugin_widgets")->where(["plugin_id" => $plugin_id])->exec();
    }
}

return new Plugin();