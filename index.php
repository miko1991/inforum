<?php
error_reporting(E_ALL);
ini_set("display_errors", "On");
ini_set("realpath_cache_size", "0");
define("IN_APP", true);
require "./Bootstrap/Autoloader.php";
use Kernel\Kernel;
Kernel::run();