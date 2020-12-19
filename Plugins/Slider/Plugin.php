<?php


namespace Plugins\Slider;
use Kernel\Interfaces\IPlugin;
use Kernel\Kernel;
use Kernel\QueryBuilder;
use Kernel\ServiceProviders\PluginServiceProvider;

class Plugin implements IPlugin
{

    public function install(): array
    {
        return [

        ];
    }

    public function getConfig(): array
    {
        return [
            "namespace" => "Slider",
            "fullName" => "Cool Slider",
            "defaultUri" => null
        ];
    }

    public function postInstall($insert_id): void
    {
        $this->addSliderWidget($insert_id);
    }

    private function addSliderWidget($insert_id) {
        $qb = new QueryBuilder();
        $qb->insert("plugin_widgets", ["plugin_id" => $insert_id, "title" => "Slider", "class_name" => "SliderWidget"])->exec();
    }

    public function cleanup(int $plugin_id): void
    {
        $qb = new QueryBuilder();
        $qb->delete("plugin_widgets")->where(["plugin_id" => $plugin_id])->exec();
    }
}

return new Plugin();