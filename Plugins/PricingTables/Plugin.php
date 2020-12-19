<?php


namespace Plugins\PricingTables;
use Kernel\Interfaces\IPlugin;
use Kernel\Kernel;

class Plugin implements IPlugin
{

    public function install(): array
    {
        $sql = file_get_contents("./Plugins/PricingTables/create_pricing_tables_table.sql");
        $stmt = Kernel::$db->prepare($sql);
        $stmt->execute();

        return [
            [
                "uri" => "/admin/pricing-tables",
                "controller" => "MainController",
                "function" => "index",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/pricing-tables/create",
                "controller" => "MainController",
                "function" => "create",
                "method" => "get",
                "middlewares" => json_encode(["Middleware\\IsAdmin"])
            ],
            [
                "uri" => "/admin/pricing-tables/create",
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
            "namespace" => "PricingTables",
            "fullName" => "Pricing Tables",
            "defaultUri" => "/admin/pricing-tables"
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