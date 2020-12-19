<?php


namespace Kernel\ServiceProviders;


use Kernel\Interfaces\IWidget;

class WidgetServiceProvider
{
    public static function loadWidget($plugin, $widget): IWidget {
        $widgetClassName = "Plugins\\".$plugin->namespace."\\".$widget->class_name;
        return new $widgetClassName();
    }
}