<?php

namespace Controllers;
use Helpers\Helpers;
use Kernel\Authentication;
use Kernel\Controller;
use Kernel\Response;
use Kernel\Template;
use Models\Group;
use Models\User;

class UsersController extends Controller {
    public function index() {
        Template::view("AdminUsersBrowse.html");
    }

    public function create() {
        $groups = Group::all();

        Template::view("AdminUsersAdd.html", "./Views/", [
            "groups" => $groups
        ]);
    }

    public function createProcess() {
        $errors = $this->validate([
            ["group" => "required"],
            ["display_name" => "required"],
            ["email" => "required.email.unique=users"],
            ["password" => "required.minLength=6.maxLength=12.sameAs=password_again"],
            ["password_again" => "required.minLength=6.maxLength=12"]
        ]);
        if (count($errors)) {
            http_response_code(400);
            Response::json($errors);
            exit();
        }

        $user = new User();
        $user->displayName = $_POST["display_name"];
        $user->email = $_POST["email"];
        $user->password = Helpers::generateHash($_POST["password"]);
        $user->group_id = $_POST["group"];
        $user->save();

        Response::json(["success" => true]);
    }

    public function browse() {
        $users = User::with(["group.permissionSet"])->get();
        Response::json($users);
    }
}