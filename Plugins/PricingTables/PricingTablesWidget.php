<?php


namespace Plugins\PricingTables;


use Kernel\Template;

class PricingTablesWidget
{
    public function get($pricing_table, $title) {
        $decodedColumns = json_decode($pricing_table->columns, true);

        Template::view("PricingTablesWidget.html", "./Plugins/PricingTables/", [
            "columns" => $decodedColumns,
            "title" => $title
        ]);
    }
}