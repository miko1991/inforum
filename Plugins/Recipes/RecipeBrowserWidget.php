<?php


namespace Plugins\Recipes;


use Kernel\Interfaces\IWidget;
use Kernel\Template;

class RecipeBrowserWidget implements IWidget
{
    public function run($widget): array
    {
        return Template::loadView("RecipeBrowserWidget.html", "./Plugins/Recipes/");
    }
}