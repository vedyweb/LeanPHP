<?php

global $config;
$config = parse_ini_file('env.ini', true);

spl_autoload_register(function ($class) {
    $prefix = 'LeanPress\\';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
