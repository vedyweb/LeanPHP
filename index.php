<?php

// require_once __DIR__ . '/bootstrap/app.php';
require_once  __DIR__ . '/core/Autoloader.php';

use leanphp\core\Autoloader;
$autoloader = new Autoloader();
$autoloader->register();

$config = parse_ini_file(__DIR__ . '/config/application.properties', true);
if ($config === false) {
    die("Error reading application.properties");
}

$activeProfile = $config['default']['leanphp.profiles.active'] ?? 'dev';
$profileConfigPath = __DIR__ . "/config/application-$activeProfile.properties";
$profileConfig = parse_ini_file($profileConfigPath, true);
if ($profileConfig === false) {
    die("Error reading application-$activeProfile.properties");
}

$config = array_merge($config['default'], $profileConfig[$activeProfile]);
foreach ($config as $key => $value) {
    putenv("$key=$value");
}

spl_autoload_register([$autoloader, 'loadClass']);

$autoloader->addNamespace(getenv('app.package.name'), __DIR__ . '/app');
$autoloader->addNamespace(getenv('app.core.package.name'), __DIR__ . '/core');




namespace leanphp\core; // veya uygun bir namespace

use DI\ContainerBuilder;
use leanphp\app\repository\UserRepository;
use leanphp\app\service\UserService;
use leanphp\app\resource\UserResource;
use leanphp\core\JwtHelper;

// DI Container Oluşturma
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    UserRepository::class => \DI\autowire(),
    JwtHelper::class => \DI\autowire(),
    UserService::class => \DI\autowire(),
    UserResource::class => \DI\autowire(),
    // Diğer bağımlılıkları da buraya ekleyebilirsiniz
]);
return $containerBuilder->build();


$requestClass = getenv('app.core.package.name') . '\\Request';
$responseClass = getenv('app.core.package.name') . '\\Response';

$routesPath = __DIR__ . '/app/routes.php';
$autoloader->loadRoutes($requestClass, $responseClass, $routesPath);
