<?php

return function ($router) {

    // router yönetimi için API rotaları
    // API routes grouped under '/api'
    $router->group('api/', function ($router) {
        // Using camelCase for method names
        // http://localhost/api/users
        $router->get('users', 'UserResource', 'getAllUsers');
        // http://localhost/api/user/1
        $router->get('user/{id}', 'UserResource', 'getUserById');
        // http://localhost/api/users
        $router->post('users','UserResource', 'createUser');
    });

    // Kimlik doğrulama ile ilgili rotalar
    $router->group('auth/', function ($router) {
        $router->post('dashboard', 'HomeResource', 'getUserProfile');
    });
    $router->addMiddleware('auth', 'getAuthenticate');

    // http://localhost/login
    $router->post('login', 'UserResource', 'login');

    // http://localhost/register
    $router->post('register', 'UserResource', 'register');

    // http://localhost/forgotPassword
    $router->post('sendMailForForgotPassword', 'UserResource', 'forgotPassword');
    
    // http://localhost/resetPassword/84b8a02e2832b5d7e9897762abe0828ba6b20687d582ad2f0b83e7398917fb48b947dfe1955713bbf4a837f08870b0035cbe
    $router->post('resetPassword/{token}', 'UserResource', 'resetPassword');

    $router->get('secret', 'HomeResource', 'secured');
    $router->get('install', 'HomeResource', 'install');
    $router->get('', 'HomeResource', 'hi');

    $router->addMiddleware('secret', 'getAuthenticate');
    $router->addMiddleware('install', 'getAuthenticate');
};
