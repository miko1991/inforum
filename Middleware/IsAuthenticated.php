<?php


namespace Middleware;
use Kernel\Authentication;
use Kernel\Response;
use Middleware\IMiddleware;

class IsAuthenticated implements IMiddleware
{

    public function handle()
    {
        if (!Authentication::user()) {
            if (Response::expectsJson()) {
                Response::json(["error" => "Not authenticated"]);
                exit();
            }
            $_SESSION["redirect"] = $_SERVER["REQUEST_URI"];
            header("Location: /auth/login");
            exit();
        } else {
            $user = Authentication::user();
            if (!$user) {
                if (Response::expectsJson()) {
                    Response::json(["error" => "Not authenticated"]);
                    exit();
                }
                $_SESSION["redirect"] = $_SERVER["REQUEST_URI"];
                header("Location: /auth/login");
                exit();
            }
        }
    }
}