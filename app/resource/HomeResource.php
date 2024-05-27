<?php

namespace leanphp\app\resource;

use leanphp\core\JwtHelper;
use leanphp\core\Request;
use leanphp\core\Response;
use leanphp\core\ErrorHandler;
use Exception;

class HomeResource {
    private $jwtHelper;
    private $errorHandler;

    public function __construct() {
        $this->jwtHelper = new JwtHelper();
        $this->errorHandler = new ErrorHandler();
    }

    public function hi() {
        $article = "Welcome to leanphp";
        $response = new Response();
    
        if(!$article) {
            return $response->withJSON(['error' => 'Article not found'], 404)->send();
        }
        return $response->withJSON($article)->send();
    }

    public function secured() {
        $article = "Welcome to Secured Area";
        $response = new Response();
    
        if (!$article) {
            return $response->withJSON(['error' => 'Article not found'], 404)->send();
        }
        return $response->withJSON($article)->send();
    }


    public function getUserProfile(Request $request, Response $response) {
        try {
            $this->jwtHelper->getAuthenticate($request, $response);
            $user = JwtHelper::user();

            if (!$user) {
                $response->withJSON(['error' => 'User not authenticated'], 401)->send();
                return;
            }
            
            $userProfile = [
                'username' => $user['identifier'],
                'role' => $user['role']
            ];
            

            $response->withJSON(['user' => $userProfile])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
            $response->withJSON(['error' => $e->getMessage()], 500)->send();
        }
    }

public function install() {
    // Yeni yapı için tekrar hazırlanacak
}

}