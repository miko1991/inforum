<?php


namespace Kernel\Interfaces;


interface IPlugin
{
    public function install(): array;
    public function postInstall($insert_id): void;
    public function getConfig(): array;
    public function cleanup(int $plugin_id): void;
}