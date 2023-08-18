<?php

namespace LeanPHP\Model;

use PDOException;

/**
 * AuthModel class.
 * Handles authentication-related database operations.
 */
class AuthModel extends BaseModel {

    // Users table name.
    protected $table = 'Users';

    /**
     * Log the error messages.
     *
     * @param string $message The error message.
     */
    private function logError($message) {
        error_log("[" . date("Y-m-d H:i:s") . "] ERROR in AuthModel: " . $message . "\n", 3, "auth_errors.log");
    }

    /**
     * Verifies if a reset token is valid and not expired.
     *
     * @param string $reset_token The token to verify.
     * @return int|false Returns the user_id if the token is valid; otherwise, returns false.
     */
    public function verifyResetToken($reset_token) {
        try {
            $sql = "SELECT user_id FROM $this->table WHERE reset_token = :reset_token AND expiry > NOW()";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':reset_token' => $reset_token]);
            $result = $stmt->fetch();

            return $result ? $result['user_id'] : false;
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while verifying reset token.");
        }
    }

    /**
     * Registers a new user.
     *
     * @param string $username User's username.
     * @param string $password User's password.
     * @param string $email User's email.
     */
    public function registerUser($username, $password, $email) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO $this->table (username, password, email) VALUES (:username, :hashed_password, :email)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':username' => $username, ':hashed_password' => $hashed_password, ':email' => $email]);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while registering user.");
        }
    }

    /**
     * Checks if a user exists based on username or email.
     *
     * @param string $username User's username.
     * @param string $email User's email.
     * @return bool True if the user exists, otherwise false.
     */
    public function doesUserExistUser($username, $email) {
        try {
            $query = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$username, $email]);
            return $stmt->fetch() ? true : false;
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while checking user existence.");
        }
    }

    /**
     * Checks if a user exists based on username.
     *
     * @param string $username User's username.
     * @return bool True if the user exists, otherwise false.
     */
    public function doesUserExistUsername($username) {
        try {
            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$username]);
            return $stmt->fetch() ? true : false;
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while checking username existence.");
        }
    }

    /**
     * Checks if a user exists based on email.
     *
     * @param string $email User's email.
     * @return bool True if the user exists, otherwise false.
     */
    public function doesUserExistMail($email) {
        try {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            return $stmt->fetch() ? true : false;
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while checking email existence.");
        }
    }

    /**
     * Retrieves a user's data based on their username.
     *
     * @param string $username User's username.
     * @return array|false User data if exists, otherwise false.
     */
    public function getUserByUsername($username) {
        try {
            $sql = "SELECT * FROM $this->table WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':username' => $username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while fetching user by username.");
        }
    }

    /**
     * Retrieves a user's data based on their username and password.
     *
     * @param string $username User's username.
     * @param string $password User's password.
     * @return array|false User data if found, otherwise false.
     */
    public function getUserByUsernameAndPassword($username, $password) {
        try {
            $sql = "SELECT * FROM $this->table WHERE username = :username AND password = :password";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':username' => $username, ':password' => md5($password)]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while fetching user by username and password.");
        }
    }

    /**
     * Stores reset token for a user.
     *
     * @param int $userId User's ID.
     * @param string $token Reset token.
     */
    public function storeResetToken($userId, $token) {
        try {
            $query = "UPDATE $this->table SET reset_token = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$token, $userId]);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while storing reset token.");
        }
    }

    /**
     * Stores authentication token and its expiry for a user.
     *
     * @param int $userId User's ID.
     * @param string $token Authentication token.
     * @param string $expiry Token's expiry time.
     */
    public function validateTokenAndExpiry($token) {
        try {
            // $sql = "SELECT * FROM $this->table WHERE token = :token";
            $sql = "SELECT * FROM users WHERE token = :token";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':token' => $token]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Token Not Found");
        }
    }

    /**
     * Stores authentication token and its expiry for a user.
     *
     * @param int $userId User's ID.
     * @param string $token Authentication token.
     * @param string $expiry Token's expiry time.
     */
    public function saveTokenAndExpiry($userId, $token, $expiry) {
        try {
            $query = "UPDATE $this->table SET token = ?, expiry = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$token, $expiry, $userId]);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while storing token and its expiry.");
        }
    }

    /**
     * Updates user's password.
     *
     * @param int $userId User's ID.
     * @param string $newPassword New password.
     */
    public function updatePassword($userId, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $query = "UPDATE $this->table SET password = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$hashedPassword, $userId]);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while updating password.");
        }
    }

    /**
     * Retrieves a user's data based on their email.
     *
     * @param string $email User's email.
     * @return array|false User data if exists, otherwise false.
     */
    public function getUserByEmail($email) {
        try {
            $query = "SELECT * FROM  $this->table WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while fetching user by email.");
        }
    }
}
