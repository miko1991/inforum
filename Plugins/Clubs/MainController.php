<?php

namespace Plugins\Clubs;

use Kernel\Kernel;
use Kernel\Template;
use Plugins\Clubs\Club;

class MainController
{
    public function index() {
        $clubs = Club::all();
        Template::view("Clubs.html", "./Plugins/Clubs/", [
            "clubs" => $clubs
        ]);
    }

    public function create() {
        Template::view("ClubsCreate.html", "./Plugins/Clubs/");
    }

    public function createProcess() {
        $club = new Club();
        $club->title = $_POST["title"];
        $club->save();
        header("Location: /admin/clubs");
    }

    public function deleteClub($id) {
        $club = Club::findById($id);
        $club->remove();

        header("Location: /admin/clubs");
    }
}