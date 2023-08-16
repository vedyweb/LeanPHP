<?php

namespace LeanPress\Core\Router;

class Router
{
    private $routes = [];
    private $middlewares = [];

    public function addMiddleware($route, $controller, $method)
    {
        $this->middlewares[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function get($path, $controller, $method)
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function post($path, $controller, $method)
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($uri)
    {
        $request = new \LeanPress\Core\Http\Request();
        $response = new \LeanPress\Core\Http\Response();
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $matched = false;
        $middlewarePassed = true;

        // Önce middlaware kontrolü yapıyor, token aldığı metodun true yada
        // false durumuna göre çalışıyor.
        foreach ($this->middlewares as $path => $middlewareDetails) {
            $pattern = "@^" . preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $path) . "$@D";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $controller = new $middlewareDetails['controller'];
                $middlewareResult = call_user_func_array([$controller, $middlewareDetails['method']], array_merge([$request, $response], $matches));
                if ($middlewareResult === false) {
                    $middlewarePassed = false;
                    echo "Authorization Failed!";
                    return; // Exiting if middleware fails
                }
                break;
            }
        }

        // Buraya hiç girmiyor:
        if ($middlewarePassed) {
            foreach ($this->routes[$requestMethod] as $path => $details) {
                $pattern = "@^" . preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $path) . "$@D";
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    $controllerName = $details['controller'];
                    $methodName = $details['method'];
                    $controller = new $controllerName;
                    call_user_func_array([$controller, $methodName], array_merge([$request, $response], $matches));
                    $matched = true;
                    break;
                }
            }

            // If no parameterized route matched, then check for direct matches.
            if (!$matched) {
                if (isset($this->routes[$requestMethod]) && isset($this->routes[$requestMethod][$uri])) {
                    $controllerName = $this->routes[$requestMethod][$uri]['controller'];
                    $methodName = $this->routes[$requestMethod][$uri]['method'];
                    $controller = new $controllerName;
                    $controller->$methodName($request, $response);
                } else {
                    // If no routes matched, return a 404 error.
                    echo "404 Not Found";
                }
            }
        }
    }
}