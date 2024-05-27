<?php 

namespace leanphp\app\resource;

use leanphp\app\service\UserService;
use leanphp\core\Request;
use leanphp\core\Response;

class UserResource {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function register(Request $request, Response $response) {
        try {
            $userData = $request->getParsedBody();
            $result = $this->userService->registerUser($userData);
            if ($result['error']) {
                return $response->withJson(['error' => $result['message']], 400)->send();
            }
            return $response->withJson(['message' => 'User registered successfully'], 201)->send();
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 400)->send();
        }
    }

/*
    public function getAllUsers(Request $request, Response $response): void {
        try {
            $users = $this->userService->getAllUsers();
            return $response->withJson($users)->send();
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500)->send();
        }
    }
    
    public function getUserById(Request $request, Response $response, $id): void {
        try {
            $user = $this->userService->getUserById($id);
            if ($user) {
                return $response->withJson($user)->send();
            } else {
                return $response->withJson(['error' => 'User not found'], 404)->send();
            }
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500)->send();
        }
    }
*/
    public function getAllUsers(Request $request, Response $response) {
        try {
            $users = $this->userService->getAllUsers();
            return $response->withJSON($users)->send();
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500)->send();
        }
    }


    public function getUserById(Request $request, Response $response, $id) {
        try {
            $user = $this->userService->getUserById($id);
            if ($user) {
                return $response->withJSON($user)->send();
            } else {
                return $response->withJson(['error' => 'User not found'], 404)->send();
            }
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500)->send();
        }
    }



    public function updateUser(Request $request, Response $response, $id) {
        try {
            $userData = $request->getParsedBody();
            $result = $this->userService->updateUser($id, $userData);
            if (!$result) {
                return $response->withJson(['error' => 'Failed to update user'], 400)->send();
            }
            return $response->withJson(['message' => 'User updated successfully'])->send();
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 400)->send();
        }
    }

    public function deleteUser(Request $request, Response $response, $id) {
        try {
            $result = $this->userService->deleteUser($id);
            if (!$result) {
                return $response->withJson(['error' => 'Failed to delete user'], 400)->send();
            }
            return $response->withJson(['message' => 'User deleted successfully'])->send();
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 400)->send();
        }
    }

    public function login(Request $request, Response $response) {
        try {
            $identifier = $request->get('email') ?: $request->get('username');
            $password = $request->get('password');

            // Null kontrolü ekleyelim
            if (is_null($identifier) || is_null($password)) {
                return $response->withJson(['error' => 'Email/Username and password are required'], 400)->send();
            }

            $token = $this->userService->loginUser($identifier, $password);

            if (!$token) {
                return $response->withJson(['error' => 'Invalid credentials'], 401)->send();
            }

            return $response->withHeader('Authorization', 'Bearer ' . $token)
            ->withJSON(['token' => $token])
            ->send();
            
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 400)->send();
        }
    }

    public function forgotPassword(Request $request, Response $response) {
        try {
            $email = $request->get('email');
            if (empty($email)) {
                return $response->withJson(['error' => 'Email is required'], 400)->send();
            }
            if ($this->userService->sendResetEmail($email)) {
                return $response->withJson(['message' => 'Reset email sent successfully'])->send();
            } else {
                return $response->withJson(['error' => 'User not found'], 404)->send();
            }
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 400)->send();
        }
    }

    public function resetPassword(Request $request, Response $response, $token) {
        try {
            $newPassword = $request->get('newPassword');
            if (empty($newPassword)) {
                return $response->withJson(['error' => 'New password is required'], 400)->send();
            }
            if ($this->userService->resetPassword($token, $newPassword)) {
                return $response->withJson(['message' => 'Password updated successfully'])->send();
            } else {
                return $response->withJson(['error' => 'Invalid or expired token'], 400)->send();
            }
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 400)->send();
        }
    }
}
