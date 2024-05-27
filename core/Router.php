<?php

namespace leanphp\core;

use leanphp\core\Logger;

class Router
{
    private $routes = [];
    private $middlewares = [];
    private $prefix = '';

    private $baseFolder;

    private $groupFolder;
    private $resourceNamespace; // ControllerNamespace yerine resourceNamespace
    private $jwtHelperNamespace;

    public function __construct()
    {
        $this->groupFolder = getenv('app.folder');
        $this->resourceNamespace = getenv('app.package.name') . '\resource\\';
        $this->jwtHelperNamespace = getenv('app.core.package.name') . '\\JwtHelper';
    }

    public function get($path, $resource, $method)
    {
        $resource = $this->resourceNamespace . $resource;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['GET'][$fullPath] = ['resource' => $resource, 'method' => $method];
    }

    public function post($path, $resource, $method)
    {
        $resource = $this->resourceNamespace . $resource;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['POST'][$fullPath] = ['resource' => $resource, 'method' => $method];
    }

    public function put($path, $resource, $method)
    {
        $resource = $this->resourceNamespace . $resource;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['PUT'][$fullPath] = ['resource' => $resource, 'method' => $method];
    }

    public function delete($path, $resource, $method)
    {
        $resource = $this->resourceNamespace . $resource;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['DELETE'][$fullPath] = ['resource' => $resource, 'method' => $method];
    }

    public function addMiddleware($path, $method)
    {
        $auth = $this->jwtHelperNamespace;
        $fullPath = $this->groupFolder . $path . '.*';
        $this->middlewares[$fullPath] = ['resource' => $auth, 'method' => $method];
    }

    public function group($prefix, $callback, $middleware = null)
    {
        $previousPrefix = $this->prefix;
        $this->prefix = $this->prefix . $this->baseFolder . $prefix;

        if ($middleware) {
            $this->addMiddleware($this->prefix, $middleware['method']);
        }

        call_user_func($callback, $this);
        $this->prefix = $previousPrefix;
    }

    public function dispatch($uri, $request, $response)
    {
        try {
            Logger::logInfo("Trying to dispatch URI: " . $uri); // Gelen URI'yi loglayın

            if (!$this->handleRouting($uri, $request, $response)) {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found', 'message' => 'No route matches the provided URI.']);
                Logger::logError("404 Not Found: No route matches the provided URI - " . $uri);

            }
        } catch (\Exception $exception) {
            Logger::logError($exception);
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error', 'message' => $exception->getMessage()]);
        }
    }

    private function handleRouting($uri, $request, $response)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $matched = false;

        foreach ($this->middlewares as $path => $middlewareDetails) {
            $pattern = "@^" . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $path) . "$@D";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $resource = new $middlewareDetails['resource'];
                $middlewareResult = call_user_func_array([$resource, $middlewareDetails['method']], array_merge([$request, $response], $matches));
                if ($middlewareResult === false) {
                    echo "Authorization Failed!";
                    return false; // Exiting if middleware fails
                }
                break;
            }
        }

        if ($matched)
            return true;

        foreach ($this->routes[$requestMethod] as $path => $details) {
            $pattern = "@^" . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $path) . "$@D";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $resourceName = $details['resource'];
                $methodName = $details['method'];
                $resource = new $resourceName;
                call_user_func_array([$resource, $methodName], array_merge([$request, $response], $matches));
                $matched = true;
                return true;
            }
        }

        // If no specific pattern matched, check if there's a direct match
        if (!$matched && isset($this->routes[$requestMethod][$uri])) {
            $resourceName = $this->routes[$requestMethod][$uri]['resource'];
            $methodName = $this->routes[$requestMethod][$uri]['method'];
            $resource = new $resourceName;
            $resource->$methodName($request, $response);
            return true;
        }

        // No routes matched at all
        return false;
    }
}