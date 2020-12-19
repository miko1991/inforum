<?php


namespace Kernel;
use Kernel\PluginWidget;
use Kernel\Kernel;
use Kernel\ServiceProviders\WidgetServiceProvider;

class PageSection
{
    public static function parse($column): array {
        switch ($column["type"]) {
            case "widget":
                return self::widget($column);
            case "members_list":
                return self::membersList();
            case "login_form":
                return self::loginForm();
        }
    }

    private static function loginForm(){
        return Template::loadView("LoginForm.html", "./Views/PageSections/");
    }

    private static function membersList(){
        $stmt = Kernel::$db->prepare("SELECT * FROM users");
        $stmt->execute();
        $members = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return Template::loadView("MembersList.html", "./Views/PageSections/", [
            "members" => $members
        ]);
    }

    private static function widget($column): array {
        $stmt = Kernel::$db->prepare("SELECT * FROM plugin_widgets WHERE id = ?");
        $stmt->execute([$column["value"]]);
        $widget = $stmt->fetch(\PDO::FETCH_OBJ);

        $stmt = Kernel::$db->prepare("SELECT * FROM plugins WHERE id = ?");
        $stmt->execute([$widget->plugin_id]);
        $plugin = $stmt->fetch(\PDO::FETCH_OBJ);

        $obj = WidgetServiceProvider::loadWidget($plugin, $widget);
        return $obj->run($widget);
    }
}