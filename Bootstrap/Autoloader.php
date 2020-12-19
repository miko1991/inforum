<?php

spl_autoload_register(function ($className) {
    $parsedClassName = str_replace('\\', '/', $className);
     if (file_exists("./".$parsedClassName.".php")) {
        require_once "./" . str_replace('\\', '/', $parsedClassName) . ".php";
     }

});