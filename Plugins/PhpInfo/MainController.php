<?php

namespace Plugins\PhpInfo;

class MainController
{
    public function index() {
        \Kernel\Template::view("PhpInfo.html", "./Plugins/PhpInfo/");
    }
}