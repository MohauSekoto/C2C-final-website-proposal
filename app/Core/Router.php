<?php
// app/Core/Router.php
namespace App\Core;

class Router {
    protected $routes = [];

    public function get($uri, $controller) {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller) {
        $this->routes['POST'][$uri] = $controller;
    }

    public function run() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash if any and base path /public
        $uri = str_replace('/public', '', $uri);
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'];

        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUri => $action) {
                // Convert {param} to regex capture group
                $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $routeUri);
                $routePattern = "#^" . $routePattern . "$#";

                if (preg_match($routePattern, $uri, $matches)) {
                    // Extract named parameters
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    if (is_callable($action)) {
                        call_user_func_array($action, $params);
                    } else {
                        list($controller, $methodName) = explode('@', $action);
                        $controllerClass = "App\\Controllers\\{$controller}";
                        
                        if (class_exists($controllerClass)) {
                            $controllerInstance = new $controllerClass();
                            if (method_exists($controllerInstance, $methodName)) {
                                call_user_func_array([$controllerInstance, $methodName], $params);
                            } else {
                                $this->abort(404);
                            }
                        } else {
                            $this->abort(404);
                        }
                    }
                    return; // Exit after successful match
                }
            }
        }
        
        $this->abort(404);
    }

    protected function abort($code = 404) {
        http_response_code($code);
        echo "404 Not Found";
        exit;
    }
}
