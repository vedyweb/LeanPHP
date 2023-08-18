<?php

require 'autoload.php';

global $config;
$config = parse_ini_file('env.ini', true);
define('PROJECT_FOLDER', '/leanphp/');

/**
 * Initialize the router.
 */
use LeanPHP\Core\Router\Router;
use LeanPHP\Core\Middleware\JwtAuth;

$router = new Router();

/*
 * User router
 * 
 * To create a new user via Postman:
 * POST http://localhost/LeanPHP/users
 */
 
$router->post(PROJECT_FOLDER . 'users', 'LeanPHP\Controller\UserControllerAPI', 'createUser');
/**
 * User routes
 *
 * To get all users via Postman:
 * GET http://localhost/LeanPHP/users
 */
//$router->addMiddleware(PROJECT_FOLDER . '/users', 'LeanPHP\Core\Middleware\JwtAuth', 'getAuthenticate');

$router->get(PROJECT_FOLDER . 'users', 'LeanPHP\Controller\UserControllerAPI', 'getAllUsers');

/**
 * Get specific user by ID
 *
 * Postman example:
 * GET http://localhost/LeanPHP/user/1
 */

// Auth Kontrol
$router->addMiddleware(PROJECT_FOLDER . 'user/{id}', 'LeanPHP\Core\Middleware\JwtAuth', 'getAuthenticate');
// If Auth Kontrol True, than get user id ....
$router->get(PROJECT_FOLDER . 'user/{id}', 'LeanPHP\Controller\UserControllerAPI', 'getUserById');



 /*
 * Authentication routes
 *
 * For user login via Postman:
 * POST http://localhost/LeanPHP/login
 * {
 *     "username": "sampleuser",
 *     "password": "samplepassword"
 * }
 */
$router->post(PROJECT_FOLDER . 'login', 'LeanPHP\Controller\AuthController', 'login');

/**
 * For user registration via Postman:
 * POST http://localhost/LeanPHP/register
 * {
 *     "username": "sampleuser",
 *     "password": "samplepassword",
 *     "email": "sample@email.com"
 * }
 */
$router->post(PROJECT_FOLDER . 'register', 'LeanPHP\Controller\AuthController', 'register');

/**
 * For password reset request via Postman:
 * POST http://localhost/LeanPHP/forgot-password
 * {
 *     "email": "sample@email.com"
 * }
 */
$router->post(PROJECT_FOLDER . 'forgot-password', 'LeanPHP\Controller\AuthController', 'forgotPassword');

/**
 * Direct password reset link (to be sent via email):
 * Example: http://localhost/LeanPHP/reset-password/12345
 */
$router->get(PROJECT_FOLDER . 'reset-password/{token}', 'LeanPHP\Controller\AuthController', 'resetPassword');

try {
    $router->dispatch($_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
    echo $e->getMessage();
}