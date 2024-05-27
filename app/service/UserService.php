<?php

namespace leanphp\app\service;

use leanphp\app\repository\UserRepository;
use leanphp\app\domain\User;
use leanphp\core\JwtHelper;
use leanphp\core\Mailer;

use InvalidArgumentException;

class UserService
{
    private $userRepository;
    private $jwtHelper;

    private $mailer;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->jwtHelper = new JwtHelper();
        $this->mailer = new Mailer();
    }
    public function registerUser(array $userData): array
    {
        if (empty($userData['firstname']) || empty($userData['lastname']) || empty($userData['email']) || empty($userData['password']) || empty($userData['username'])) {
            throw new InvalidArgumentException("All fields are required");
        }

        $user = new User();
        $user->setFirstname($userData['firstname']);
        $user->setLastname($userData['lastname']);
        $user->setEmail($userData['email']);
        $user->setPassword($userData['password']);
        $user->setUsername($userData['username']);
        $user->setRole($userData['role'] ?? 'user');
        $user->setAvatarUrl($userData['avatar_url'] ?? null);
        $user->setCreatedAt(date('Y-m-d H:i:s'));
        $user->setUpdatedAt(date('Y-m-d H:i:s'));
        $user->setLastLogin(date('Y-m-d H:i:s'));
        $user->setStatus('active');
        $user->setCreatedBy(1);
        $user->setUpdatedBy(1);
        $user->setAddress($userData['address'] ?? null);
        $user->setPhone($userData['phone'] ?? null);
        $user->setDateOfBirth($userData['date_of_birth'] ?? null);
        $user->setBio($userData['bio'] ?? null);
        $user->setWebsiteUrl($userData['website_url'] ?? null);
        $user->setTwitterHandle($userData['twitter_handle'] ?? null);
        $user->setLinkedinProfile($userData['linkedin_profile'] ?? null);
        return $this->userRepository->registerUser($user);
    }


    public function loginUser(string $identifier, string $password): ?string
    {
        $user = $this->getUserByEmailOrUsername($identifier);
        if ($user && password_verify($password, $user->getPassword())) {

            $payload =
                [
                    "sub" => $user->getId(),
                    "identifier" => $user->getUsername(),
                    "role" => $user->getRole(),
                    "iat" => time()
                ];
            $tokenValidityInSeconds = 3600; // 1 hour
            $token = $this->jwtHelper->createJWT($payload, $tokenValidityInSeconds);
            $expiry = date('Y-m-d H:i:s', time() + $tokenValidityInSeconds);

            $this->userRepository->saveTokenAndExpiry($user->getId(), $token, $expiry);
            return $token;
        }
        return null;
    }

    public function getUserByEmailOrUsername(string $identifier): ?User
    {
        return $this->userRepository->getUserByEmailOrUsername($identifier);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function updateUser(int $id, array $userData): bool
    {
        return $this->userRepository->update($id, $userData);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $userId = $this->userRepository->verifyResetToken($token);
        if ($userId) {
            return $this->userRepository->updateUserPassword($userId, $newPassword);
        }
        return false;
    }



    public function sendResetEmail(string $email): bool
    {
        $user = $this->getUserByEmailOrUsername($email);
        if ($user) {

            $resetToken = bin2hex(random_bytes(50));
            $this->userRepository->storeResetToken($user->getId(), $resetToken);

            $resetLink = getenv('APP_URL') . getenv('APP_FOLDER') . "resetPassword/{$resetToken}";
            $this->generateEmail($email, $resetLink);

            return $resetLink;
        }
        return false;
    }

    private function generateEmail($email, $resetLink)
    {
        $subject = 'Password Reset Request';
        $bodyContent = "Please click the following link to reset your password: <a href='{$resetLink}'>Reset Password</a>";
        $this->mailer->sendEmail($email, $subject, $bodyContent);
    }
}
