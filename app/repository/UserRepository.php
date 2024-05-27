<?php

namespace leanphp\app\Repository;

use leanphp\app\domain\User;
use PDO;
use PDOException;
use Exception;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public function registerUser(User $user): array {
 
        try {
            $sql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':username' => $user->getUsername(), ':email' => $user->getEmail()]);
            
            if ($stmt->fetchColumn() > 0) {
                return ['error' => true, 'message' => 'Username or email already exists'];
            } else {
                $hashed_password = password_hash($user->getPassword(), PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password, email, role) VALUES (:username, :hashed_password, :email, :role)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':username' => $user->getUsername(), ':hashed_password' => $hashed_password, ':email' => $user->getEmail(), ':role' => $user->getRole()]);
                return ['error' => false, 'message' => 'User registered successfully'];
            }
        } catch (PDOException $e) {
            throw new Exception("Database error while registering user.");
        }
    }

    public function getUserByEmailOrUsername(string $identifier): ?User {
        try {
            $sql = "SELECT * FROM users WHERE email = :identifier OR username = :identifier";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':identifier' => $identifier]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return new User($result);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching user by email or username: " . $e->getMessage());
        }
    }

    public function saveTokenAndExpiry(int $userId, string $token, string $expiry): bool {
        try {
            $sql = "UPDATE users SET token = :token, expiry = :expiry WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':token' => $token, ':expiry' => $expiry, ':id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Database error while saving token and expiry: " . $e->getMessage());
        }
    }

    public function verifyResetToken(string $resetToken) {
        try {
            // $sql = "SELECT id, reset_token FROM users WHERE reset_token = :reset_token AND expiry > NOW()";
            $sql = "SELECT id FROM users WHERE reset_token = :reset_token";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':reset_token' => $resetToken]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : false;
        } catch (PDOException $e) {
            throw new Exception("Database error while verifying reset token: " . $e->getMessage());
        }
    }

    public function updateUserPassword(int $userId, string $newPassword): bool {
        try {
            $sql = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':password' => password_hash($newPassword, PASSWORD_DEFAULT), ':id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Database error while updating user password: " . $e->getMessage());
        }
    }

    public function storeResetToken(int $userId, string $resetToken): bool {
        try {
            $sql = "UPDATE users SET reset_token = :reset_token WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':reset_token' => $resetToken, ':id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Database error while storing reset token: " . $e->getMessage());
        }
    }
}