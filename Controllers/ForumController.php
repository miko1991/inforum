<?php


namespace Controllers;


use Kernel\Controller;
use Kernel\Response;
use Kernel\Template;
use Models\Forum;

class ForumController extends Controller
{
    public function index() {
        Template::view("AdminForumsBrowse.html");
    }

    public function create() {
        Template::view("AdminForumCreate.html");
    }

    public function createProcess() {
        $errors = $this->validate([
            ["title" => "required"]
        ]);
        if (count($errors)) {
            http_response_code(400);
            Response::json($errors);
            exit();
        }

        $forum = new Forum();
        $forum->title = $_POST["title"];
        $forum->parent_id = $_POST["parent_id"] ? $_POST["parent_id"] : null;
        $forum->save();

        Response::json(["success" => true]);
    }

    public function getAllParentNull() {
        $forums = Forum::where(["parent_id" => null])->get();
        Response::json($forums);
    }

    public function getAll() {
        $forums = Forum::all();
        Response::json($forums);
    }
}