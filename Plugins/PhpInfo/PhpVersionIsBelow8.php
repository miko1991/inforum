<?php


namespace Plugins\PhpInfo;
use Middleware\IMiddleware;

class PhpVersionIsBelow8 implements IMiddleware
{

    public function handle()
    {
        if (PHP_VERSION < 8) {
            exit("PHP VERSION IS BELOW 8! VERSION: " . PHP_VERSION);
        }
    }
}