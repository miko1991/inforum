<?php


namespace Kernel;


class Config
{
    public static function exists(): bool {
        return file_exists("./config.json");
    }

    public static function load() {
        $file = "./config.json";
        if (!file_exists($file)) return null;
        $jsonFile = file_get_contents($file);
        return json_decode($jsonFile);
    }
}