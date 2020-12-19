<?php


namespace Controllers;


use Helpers\Helpers;
use Kernel\Authentication;
use Kernel\Response;
use Kernel\Template;
use Models\Menu;
use Models\MenuItem;

class MenuController
{
    public function index() {
        Template::view("AdminMenusBrowse.html");
    }

    public function create() {
        Template::view("AdminMenuAdd.html");
    }

    public function getActiveMenu() {
        $user = Authentication::user();
        $menu = Menu::with(["items"])->where(["is_active" => 1])->first();
        Response::json(["user" => $user, "menu" => $menu]);
    }

    public function createProcess() {
        $menu = new Menu();
        $menu->title = $_POST["title"];
        $menu->is_active = 0;
        $menu->save();

        $items = json_decode($_POST["items"]);
        foreach ($items as $item) {
            $menuItem = new MenuItem();
            $menuItem->title = $item->title;
            $menuItem->uri = $item->uri;
            $menuItem->menu_id = $menu->id;
            $menuItem->display_conditions = json_encode($item->display_conditions);
            $menuItem->save();
        }

        Response::json([]);
    }

    public function browse() {
        $menus = Menu::with(["items"])->get();
        Response::json($menus);
    }
}