<?php

namespace leanphp\core;

use Exception;

/*
class Autoloader
{
    private $prefixes = [];
    private $baseDirs = [];
    private $router;

    public function __construct($prefix, array $baseDirs)
    {
        $this->prefix = $prefix;
        $this->baseDirs = $baseDirs;
    }

    public function loadClass()
    {
        spl_autoload_register([$this, 'classLoader']);
    }

    public function loadRoutes($requestClass, $responseClass, $routeFile)
    {
        try {
            $this->router = new Router();
            $routes = require $routeFile;
            $routes($this->router);

            $request = new $requestClass();
            $response = new $responseClass();

            $this->router->dispatch($_SERVER['REQUEST_URI'], $request, $response);
        } catch (Exception $e) {
            echo $e->getMessage(); // Basic error output for now
        }
    }

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    public function addNamespace($prefix, $baseDir, $prepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = [];
        }

        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            array_push($this->prefixes[$prefix], $baseDir);
        }
    }


    public function loadClass($class)
    {
        $prefix = $class;
        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            $relativeClass = substr($class, $pos + 1);
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);

            if ($mappedFile) {
                return $mappedFile;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    protected function loadMappedFile($prefix, $relativeClass)
    {
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        foreach ($this->prefixes[$prefix] as $baseDir) {
            $file = $baseDir
                  . str_replace('\\', '/', $relativeClass)
                  . '.php';

            if ($this->requireFile($file)) {
                return $file;
            }
        }

        return false;
    }

    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }

        return false;
    }
}
*/


namespace leanphp\core;

use leanphp\core\Router;
use Exception;

class Autoloader
{
    private $prefixes = [];

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    public function addNamespace($prefix, $baseDir, $prepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }

        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            array_push($this->prefixes[$prefix], $baseDir);
        }
    }

    public function loadClass($class)
    {
        $prefix = $class;
        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            $relativeClass = substr($class, $pos + 1);
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);

            if ($mappedFile) {
                return $mappedFile;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    protected function loadMappedFile($prefix, $relativeClass)
    {
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }

        foreach ($this->prefixes[$prefix] as $baseDir) {
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if ($this->requireFile($file)) {
                return $file;
            }
        }

        return false;
    }

    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }

        return false;
    }

    public function loadRoutes($requestClass, $responseClass, $routeFile)
    {
        try {
            $this->router = new Router();
            $routes = require $routeFile;
            $routes($this->router);

            $request = new $requestClass();
            $response = new $responseClass();

            $this->router->dispatch($_SERVER['REQUEST_URI'], $request, $response);
        } catch (Exception $e) {
            echo $e->getMessage(); // Basic error output for now
        }
    }
}
