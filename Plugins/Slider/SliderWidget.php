<?php


namespace Plugins\Slider;


use Kernel\Interfaces\IWidget;
use Kernel\Kernel;
use Kernel\Template;

class SliderWidget implements IWidget
{
    public function run($widget): array {
        return Template::loadView("SliderWidget.html", "./Plugins/Slider/", [
            "slider_widget" => $widget
        ]);
    }
}