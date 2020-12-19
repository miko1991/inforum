<?php

namespace Plugins\PricingTables;

use Kernel\Kernel;
use Kernel\Template;

class MainController
{
    public function index() {
        $stmt = Kernel::$db->prepare("SELECT plugin_widgets.id as widget_id, plugin_widgets.title as widget_title FROM plugin_widgets WHERE table_name = ?");
        $stmt->execute(["pricing_tables"]);
        $widgets = $stmt->fetchAll(\PDO::FETCH_OBJ);
        Template::view("PricingTable.html", "./Plugins/PricingTables/", [
            "widgets" => $widgets
        ]);
    }

    public function create() {
        Template::view("PricingTableCreate.html", "./Plugins/PricingTables/");
    }

    public function createProcess() {
        $stmt = Kernel::$db->prepare("INSERT INTO pricing_tables (columns) VALUES (?)");
        $stmt->execute([$_POST["columns"]]);
        $resource_id = Kernel::$db->lastInsertId();

        $stmt = Kernel::$db->prepare("SELECT * FROM plugins WHERE namespace = ?");
        $stmt->execute(["PricingTables"]);
        $plugin = $stmt->fetch(\PDO::FETCH_OBJ);

        try {
            $stmt = Kernel::$db->prepare("INSERT INTO plugin_widgets (plugin_id, title, resource_id, table_name, class_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$plugin->id, $_POST["title"], $resource_id, "pricing_tables", "PricingTablesWidget"]);
        } catch (\Exception $exception) {
            exit($exception->getMessage());
        }

    }
}