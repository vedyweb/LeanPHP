<?php

namespace LeanPHP\Controller;

use LeanPHP\Core\Http\Request;
use LeanPHP\Core\Http\Response;
use LeanPHP\Model\AuthModel;
use LeanPHP\Helpers\JwtHelper;
use Exception;

class AuthController {
    private $userAuthModel;

    /**
     * AuthController constructor.
     */
    public function __construct() {
        $this->userAuthModel = new AuthModel();
    }

    /**
     * Handles user registration.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function register(Request $request, Response $response) {
        try {
            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');

            if (empty($username) || empty($password) || empty($email)) {
                return $response->json(['error' => 'All fields are required'], 400);
            }

            if ($this->userAuthModel->doesUserExistUser($username, $email) ||
                $this->userAuthModel->doesUserExistUsername($username) ||
                $this->userAuthModel->doesUserExistMail($email)) {
                return $response->json(['error' => 'Username or email already exists'], 409);
            }

            $this->userAuthModel->registerUser($username, $password, $email);

            return $response->json(['message' => 'User registered successfully']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $response->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Handles user login.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function login(Request $request, Response $response) {
        try {
            $username = $request->get('username');
            $password = $request->get('password');
            $user = $this->userAuthModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $jwtHelper = new JwtHelper();
                $payload = ["sub" => $user['user_id'], "name" => $user['username'], "iat" => time()];

                $tokenValidityInSeconds = 24 * 60 * 60;
                $token = $jwtHelper->createJWT($payload, $tokenValidityInSeconds);
                $expiryDate = date('Y-m-d H:i:s', time() + $tokenValidityInSeconds);

                $this->userAuthModel->saveTokenAndExpiry($user['user_id'], $token, $expiryDate);

                return $response->json(['token' => $token]);
            } else {
                return $response->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $response->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Handles forgotten passwords, sends a reset email.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function forgotPassword(Request $request, Response $response) {
        try {
            $email = $request->get('email');
            if (empty($email)) {
                return $response->json(['error' => 'Email is required'], 400);
            }

            $user = $this->userAuthModel->getUserByEmail($email);

            if (!$user) {
                return $response->json(['error' => 'User not found'], 404);
            }

            $resetToken = bin2hex(random_bytes(50));
            $this->userAuthModel->storeResetToken($user['user_id'], $resetToken);
            $resetLink = "/LeanPHP/reset-password/{$resetToken}";

            if (!$this->sendResetEmail($user['email'], $resetLink)) {
                throw new Exception("Failed to send reset email.");
            }

            return $response->json(['resetLink' => $resetLink]);
        } catch (Exception $e) {
            //error_log($e->getMessage());
            return $response->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Resets the user's password.
     *
     * @param Request $request
     * @param Response $response
     * @param string $token
     * @return Response
     */
    public function resetPassword(Request $request, Response $response, $token) {
        try {
            if (empty($token)) {
                return $response->json(['error' => 'Token is required'], 400);
            }

            $userId = $this->userAuthModel->verifyResetToken($token);
            if (!$userId) {
                return $response->json(['error' => 'Invalid or expired token'], 401);
            }

            // $newPassword = $request->get('new_password');
            $newPassword = "admin";

            if (empty($newPassword)) {
                return $response->json(['error' => 'New password is required'], 400);
            }

            $this->userAuthModel->updatePassword($userId, $newPassword);
            return $response->json(['message' => 'Password reset successfully']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $response->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Generates the reset password email's HTML body.
     *
     * @param string $link
     * @return string
     */
    private function generateEmailBody($link) {
        return "
            <html>
            <body>
            <h2>LeanPHP Password Reset</h2>
            <p>Click the link below to reset your password:</p>
            <a href='{$link}'>Reset Password</a>
            </body>
            </html>
        ";
    }

    /**
     * Sends the reset email.
     *
     * @param string $email
     * @param string $link
     * @return bool
     */
    private function sendResetEmail($email, $link) {
        $to = $email;
        $subject = "LeanPHP Password Reset";
        $message = $this->generateEmailBody($link);
        $headers = "From: no-reply@LeanPHP.com\r\n";
        $headers .= "Content-type: text/html\r\n";
        echo "email gönderdim linkle" . $link;
        //return mail($to, $subject, $message, $headers);
    }
}
