<?php


namespace Controllers;


use Kernel\Response;
use Kernel\Updater;

class UpdateController
{
    public function checkHasUpdate() {
        $hasUpdate = Updater::checkHasUpdate();
        Response::json(["hasUpdate" => $hasUpdate]);
    }

    public function update() {
        $updates = json_decode($_POST["updates"], true);
        try {
            $dirname = Updater::update($updates);
            Response::json(["response" => $dirname]);
        } catch (\Exception $exception) {
            Response::json(["error" => $exception->getMessage()]);
        }

    }
}