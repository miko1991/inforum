<?php


namespace Models;
use Kernel\Model;
use Models\MenuItem;

class Menu extends Model
{
    public string $table = "menus";

    public function items() {
        return $this->hasMany(MenuItem::class, "menu_id");
    }
}