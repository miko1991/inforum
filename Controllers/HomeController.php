<?php


namespace Controllers;


use Kernel\Authentication;
use Kernel\Template;

class HomeController
{
    public function index() {
        $user = Authentication::user();
        Template::view("Home.html", "./Views/", [
            "user" => $user
        ]);
    }
}