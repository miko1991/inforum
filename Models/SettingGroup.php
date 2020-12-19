<?php


namespace Models;

use Kernel\Model;
use Models\Setting;

class SettingGroup extends Model
{
    public string $table = "setting_groups";

    public function settings()
    {
        return $this->hasMany(Setting::class, "group_id");
    }
}
