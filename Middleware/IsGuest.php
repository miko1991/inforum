<?php


namespace Middleware;
use Kernel\Authentication;
use Kernel\Response;
use Middleware\IMiddleware;

class IsGuest implements IMiddleware
{

    public function handle()
    {
        if (Authentication::user()) {
            if (Response::expectsJson()) {
                Response::json(["error" => "Not a guest"]);
                exit();
            }
            header("Location: /");
            exit();
        }
    }
}