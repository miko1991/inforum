<?php


namespace Middleware;
use Kernel\Authentication;
use Kernel\Kernel;
use Kernel\Response;
use Middleware\IMiddleware;

class IsAdmin implements IMiddleware
{

    public function handle()
    {
        $user = Authentication::user();
        if (!$user->group->permissionSet->can_access_acp) {
            if (Response::expectsJson()) {
                Response::json(["error" => "Not an admin"]);
                exit();
            }

            header("Location: /");
            exit();
        }
    }
}