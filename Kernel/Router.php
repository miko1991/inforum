<?php


namespace Kernel;

use Kernel\Kernel;
use Controllers\HomeController;
use Controllers\InstallationController;
use Middleware\CheckIsInstalled;
use Middleware\IMiddleware;

class Router
{
    private static $routes = [];

    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    public static function parseRoutes() {


        if (Config::exists() && Kernel::checkRoutesTableExists()){
            self::loadDatabaseRoutes();
            self::loadPluginRoutes();

        }

        self::run("/");
    }

    private static function loadPluginRoutes() {
        if (!file_exists("./config.json")) return;
        $stmt = Kernel::$db->prepare("SELECT plugin_routes.method, plugin_routes.uri, plugin_routes.controller, plugin_routes.function, plugin_routes.middlewares, plugins.namespace FROM plugin_routes LEFT JOIN plugins ON plugin_routes.plugin_id = plugins.id");
        $stmt->execute();
        $routes = $stmt->fetchAll(\PDO::FETCH_OBJ);


        foreach ($routes as $route) {
            self::add($route->uri,
                "Plugins\\".$route->namespace."\\".$route->controller."@".$route->function,
                $route->method,
                json_decode($route->middlewares, true),
            );
        }
    }

    private static function loadDatabaseRoutes() {
        if (!file_exists("./config.json")) return;
        $stmt = Kernel::$db->prepare("SELECT * FROM routes");
        $stmt->execute();
        $routes = $stmt->fetchAll(\PDO::FETCH_OBJ);

        foreach ($routes as $route) {
            self::add($route->uri,
                "Controllers\\".$route->controller."@".$route->function,
                $route->method,
                json_decode($route->middlewares, true),
            );
        }
    }

    public static function get($expression, $function, $middlewares = []) {
        self::add($expression, "Controllers\\".$function, "get", $middlewares);
    }

    public static function post($expression, $function, $middlewares = []) {
        self::add($expression, "Controllers\\".$function, "post", $middlewares);
    }

    public static function add($expression, $function, $method = 'get', $middlewares = []){
        array_push(self::$routes, Array(
            'expression' => $expression,
            'function' => $function,
            'method' => $method,
            'middlewares' => $middlewares
        ));
    }

    public static function pathNotFound($function){
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed($function){
        self::$methodNotAllowed = $function;
    }

    public static function run($basepath = '/'){

        // Parse current url
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri

        if(isset($parsed_url['path'])){
            $path = $parsed_url['path'];
        }else{
            $path = '/';
        }

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $path_match_found = false;

        $route_match_found = false;

        foreach(self::$routes as $route){

            // If the method matches check the path

            // Add basepath to matching string
            if($basepath!=''&&$basepath!='/'){
                $route['expression'] = '('.$basepath.')'.$route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^'.$route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'].'$';

            // echo $route['expression'].'<br/>';

            // Check path match
            if(preg_match('#'.$route['expression'].'#',$path,$matches)){

                $path_match_found = true;

                // Check method match
                if(strtolower($method) == strtolower($route['method'])){

                    array_shift($matches);// Always remove first element. This contains the whole string

                    if($basepath!=''&&$basepath!='/'){
                        array_shift($matches);// Remove basepath
                    }

                    if (is_callable($route["function"])) {
                        call_user_func_array($route['function'], $matches);
                        $route_match_found = true;
                    } elseif (is_string($route["function"])) {
                        if (preg_match('#^([a-zA-Z\\\]+)@([a-zA-Z]+)$#', $route["function"], $controllerMatches)) {
                            $route_match_found = true;
                            array_shift($controllerMatches);
                            $controller = $controllerMatches[0];
                            $controllerMethod = $controllerMatches[1];
                            self::loadController($controller,
                                $controllerMethod,
                                $route["middlewares"],
                                $matches);
                        }
                    }

                    // Do not check other routes
                    break;
                }
            }
        }

        // No matching route was found
        if(!$route_match_found){

            // But a matching path exists
            if($path_match_found){
                header("HTTP/1.0 405 Method Not Allowed");
                if(self::$methodNotAllowed){
                    call_user_func_array(self::$methodNotAllowed, Array($path,$method));
                }
            }else{
                header("HTTP/1.0 404 Not Found");
                if(self::$pathNotFound){
                    call_user_func_array(self::$pathNotFound, Array($path));
                }
            }

        }

    }

    public static function loadController($controller, $method, $middlewares = [], $params) {
        foreach ($middlewares as $middleware) {
            if (!$middleware) continue;
            $obj = new $middleware();
            $obj->handle();
        }

        $class = $controller;
        $obj = new $class();
        call_user_func_array(array($obj, $method), $params);
    }
}
