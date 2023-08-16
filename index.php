<?php

require 'autoload.php';

/**
 * Initialize the router.
 */
use LeanPress\Core\Router\Router;
use LeanPress\Core\Middleware\JwtAuth;

$router = new Router();

/**
 * User routes
 *
 * To get all users via Postman:
 * GET http://localhost/leanpress/users
 */
//$router->addMiddleware('/leanpress/users', 'LeanPress\Core\Middleware\JwtAuth', 'getAuthenticate');

$router->get('/leanpress/users', 'LeanPress\Controller\UserControllerAPI', 'getAllUsers');

/**
 * Get specific user by ID
 *
 * Postman example:
 * GET http://localhost/leanpress/user/1
 */

// Auth Kontrol
$router->addMiddleware('/leanpress/user/{id}', 'LeanPress\Core\Middleware\JwtAuth', 'getAuthenticate');
// If Auth Kontrol True, than get user id ....
$router->get('/leanpress/user/{id}', 'LeanPress\Controller\UserControllerAPI', 'getUserById');



 /*
 * Authentication routes
 *
 * For user login via Postman:
 * POST http://localhost/leanpress/login
 * {
 *     "username": "sampleuser",
 *     "password": "samplepassword"
 * }
 */
$router->post('/leanpress/login', 'LeanPress\Controller\AuthController', 'login');

/**
 * For user registration via Postman:
 * POST http://localhost/leanpress/register
 * {
 *     "username": "sampleuser",
 *     "password": "samplepassword",
 *     "email": "sample@email.com"
 * }
 */
$router->post('/leanpress/register', 'LeanPress\Controller\AuthController', 'register');

/**
 * For password reset request via Postman:
 * POST http://localhost/leanpress/forgot-password
 * {
 *     "email": "sample@email.com"
 * }
 */
$router->post('/leanpress/forgot-password', 'LeanPress\Controller\AuthController', 'forgotPassword');

/**
 * Direct password reset link (to be sent via email):
 * Example: http://localhost/leanpress/reset-password/12345
 */
$router->get('/leanpress/reset-password/{token}', 'LeanPress\Controller\AuthController', 'resetPassword');

try {
    $router->dispatch($_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
    echo $e->getMessage();
}