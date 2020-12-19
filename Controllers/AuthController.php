<?php


namespace Controllers;


use Kernel\Authentication;
use Kernel\Controller;
use Kernel\Display;
use Kernel\Response;
use Kernel\Template;
use Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $user = Authentication::user();
        Template::view("Login.html", "./Views/", [
            "user" => $user
        ]);
    }

    public function checkToken() {
        if (!$_POST["email"]) {
            return Response::json(["isCorrect" => false]);
        }
        $user = User::where(["email" => $_POST["email"]])->first();
        if (!$user) {
            return Response::json(["isCorrect" => false]);
        }
        if ($user->token != $_POST["token"]) {
            Response::json(["isCorrect" => false]);
        } else {
            Authentication::login($user);
            $user->token = "";
            $user->save();
            Response::json(["success" => true]);
        }
    }

    public function loginProcess()
    {
        $errors = $this->validate([
            ["email" => "required"],
            ["password" => "required"],
        ]);


        if (count($errors)) {
            http_response_code(400);
            Response::json($errors);
            exit();
        }

        $user = User::where(["email" => $_POST["email"]])->first();
        if (!$user) {
            http_response_code(400);
            Response::json(["message" => "Could not log you in."]);
            exit();
        }

        if (!Authentication::check($_POST["password"], $user)) {
            http_response_code(400);
            Response::json(["message" => "Could not log you in."]);
            exit();
        }

        if (!Authentication::checkTwoFactorAuthenticationEnabled()) {
            Authentication::login($user);
            Response::json(["success" => "true"]);
            exit();
        } else {
            Authentication::sendRandomToken($user);
            Response::json(["two_factor_enabled" => true, "email" => $user->email]);
        }

    }

    public function register()
    {
        $error = Display::flash("error");
        $errors = Display::flash("errors");
        $user = Authentication::user();

        Template::view("Register.html", "./Views/", [
            "error" => $error,
            "errors" => $errors,
            "user" => $user
        ]);
    }

    public function registerProcess()
    {
        $requiredFields = ["display_name" => "Display Name", "email" => "Email", "password" => "Password", "password_again" => "Password Confirmation"];

        $errors = [];
        foreach ($requiredFields as $field => $value) {
            if (!$_POST[$field]) {
                $errors[$field] = $value . " is required";
            }
        }

        if (count($errors)) {
            Display::flash("errors", $errors);
            header("Location: /auth/register");
            exit();
        }

        Authentication::createUser($_POST["display_name"], $_POST["email"], $_POST["password"]);

        header("Location: /auth/login");
    }

    public function logout()
    {
        Authentication::logout();
        header("Location: /");
    }
}
