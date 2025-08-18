<?php

class Router {
    private $routes = [];

    public function addRoute($method, $path, $callback) {
        $this->routes[] = ["method" => $method, "path" => $path, "callback" => $callback];
    }

    public function get($path, $callback) {
        $this->addRoute("GET", $path, $callback);
    }

    public function post($path, $callback) {
        $this->addRoute("POST", $path, $callback);
    }

    public function dispatch() {
        $uri = strtok($_SERVER["REQUEST_URI"], "?");
        $method = $_SERVER["REQUEST_METHOD"];
        
        error_log("Processing request: $method $uri");

        foreach ($this->routes as $route) {
            if ($route["method"] === $method) {
                $pattern = preg_replace("/{([a-zA-Z0-9_]+)}/", "([a-zA-Z0-9_]+)", $route["path"]);
                $fullPattern = "#^" . $pattern . "$#";
                error_log("Checking route: " . $route["path"] . " with pattern: " . $fullPattern);
                
                if (preg_match($fullPattern, $uri, $matches)) {
                    error_log("Route matched! Matches: " . print_r($matches, true));
                    array_shift($matches); // Remove full match

                    $callback = $route["callback"];

                    if (is_callable($callback)) {
                        call_user_func_array($callback, $matches);
                    } elseif (is_string($callback)) {
                        list($controller, $action) = explode("@", $callback);
                        $controllerName = $controller;
                        $controllerFile = __DIR__ . "/../Controllers/" . $controllerName . ".php";

                        if (file_exists($controllerFile)) {
                            require_once $controllerFile;
                            $controllerInstance = new $controller();
                            if (method_exists($controllerInstance, $action)) {
                                call_user_func_array([$controllerInstance, $action], $matches);
                            } else {
                                echo "Action " . $action . " not found in controller " . $controllerName;
                            }
                        } else {
                            echo "Controller " . $controllerName . " not found.";
                        }
                    }
                    return;
                }
            }
        }

        // Handle 404
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . "/../Views/errors/404.php";
    }
}

?>

