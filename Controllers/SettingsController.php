<?php


namespace Controllers;


use Helpers\Helpers;
use Kernel\Authentication;
use Kernel\Response;
use Kernel\Template;
use Models\Menu;
use Models\MenuItem;
use Models\SettingGroup;
use Models\Setting;

class SettingsController
{
    public function index()
    {
        $settings = SettingGroup::with(["settings"])->get();
        Template::view("AdminSettings.html", "./Views/", [
            "settings" => $settings
        ]);
    }

    public function save()
    {
        $settings = json_decode($_POST["settings"]);

        foreach ($settings as $settingData) {
            $setting = Setting::where(["prop" => $settingData->prop])->first();
            $setting->value = $settingData->value;
            $setting->save();
        }

        Response::json(["success" => true]);
    }
}
