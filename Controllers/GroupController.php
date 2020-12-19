<?php


namespace Controllers;


use Helpers\Helpers;
use Kernel\Controller;
use Kernel\Response;
use Kernel\Template;
use Models\Group;
use Models\PermissionSet;

class GroupController extends Controller
{
    public function index() {
        Template::view("AdminGroupsBrowse.html");
    }

    public function create() {
        Template::view("AdminGroupAdd.html");
    }

    public function createProcess() {
        $errors = $this->validate([
            ["title" => "required"],
        ]);
        if (count($errors)) {
            http_response_code(400);
            Response::json($errors);
            exit();
        }

        $permissionSet = new PermissionSet();
        $permissionSet->can_access_acp = $_POST["can_access_acp"];
        $permissionSet->save();

        $group = new Group();
        $group->title = $_POST["title"];
        $group->permission_set_id = $permissionSet->id;
        $group->save();


        Response::json(["success" => true]);
    }

    public function browse() {
        $groups = Group::all();
        Response::json($groups);
    }
}