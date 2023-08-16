<?php

require 'ErrorHandler.php';

class UserController {
    
    private $userModel;
    private $errorHandler;

    public function __construct(UserModel $model, ErrorHandler $errorHandler) {
        $this->userModel = $model;
        $this->errorHandler = $errorHandler;
    }

    public function index(Request $request, Response $response): void {
        try {
            $users = $this->userModel->getAllUsers();
            echo "<h1>Tüm Kullanıcılar</h1>";
            foreach ($users as $user) {
                echo "<p>{$user['username']}</p>";
            }
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }

    public function createUser(Request $request, Response $response): void {
        try {
            $userData = $request->getParsedBody();
            $newUser = $this->userModel->createUser($userData);
            echo "<h1>Kullanıcı Oluşturuldu</h1>";
            echo "<p>Yeni Kullanıcı Adı: {$newUser['username']}</p>";
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }

    public function updateUser(Request $request, Response $response, $id): void {
        try {
            $userData = $request->getParsedBody();
            $updatedUser = $this->userModel->updateUser($id, $userData);
            echo "<h1>Kullanıcı Güncellendi</h1>";
            echo "<p>Güncellenen Kullanıcı Adı: {$updatedUser['username']}</p>";
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }

    public function deleteUser(Request $request, Response $response, $id): void {
        try {
            $this->userModel->deleteUser($id);
            echo "<h1>Kullanıcı Silindi</h1>";
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }

    public function getUser(Request $request, Response $response, $id): void {
        try {
            $user = $this->userModel->getUserById($id);
            echo "<h1>Kullanıcı Detayı</h1>";
            echo "<p>Adı: {$user['username']}</p>";
            echo "<p>Email: {$user['email']}</p>";
            // Diğer özellikleri de burada yazdırabilirsiniz.
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }

    public function searchUser(Request $request, Response $response, $keyword): void {
        try {
            $users = $this->userModel->searchUser($keyword);
            echo "<h1>Arama Sonuçları: {$keyword}</h1>";
            foreach ($users as $user) {
                echo "<p>{$user['username']}</p>";
            }
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }

    public function countUsers(Request $request, Response $response): void {
        try {
            $count = $this->userModel->countUsers();
            echo "<h1>Kullanıcı Sayısı</h1>";
            echo "<p>{$count}</p>";
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }

    public function filterUsers(Request $request, Response $response, $filter): void {
        try {
            $users = $this->userModel->filterUsers($filter);
            echo "<h1>Filtrelenmiş Kullanıcılar: {$filter}</h1>";
            foreach ($users as $user) {
                echo "<p>{$user['username']}</p>";
            }
        } catch (Exception $e) {
            $this->errorHandler->displayError($e);
        }
    }
}