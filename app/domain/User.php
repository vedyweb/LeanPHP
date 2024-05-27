<?php

namespace leanphp\app\domain;

class User {
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $role;
    private $avatar_url;
    private $created_at;
    private $updated_at;
    private $username;
    private $password;
    private $token;
    private $expiry;
    private $reset_token;
    private $last_login;
    private $status;
    private $created_by;
    private $updated_by;
    private $address;
    private $phone;
    private $date_of_birth;
    private $bio;
    private $website_url;
    private $twitter_handle;
    private $linkedin_profile;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->firstname = $data['firstname'] ?? null;
            $this->lastname = $data['lastname'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->role = $data['role'] ?? null;
            $this->avatar_url = $data['avatar_url'] ?? null;
            $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
            $this->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
            $this->username = $data['username'] ?? null;
            $this->password = $data['password'] ?? null; // Şifrenin doğru şekilde set edilmesini sağla
            $this->token = $data['token'] ?? null;
            $this->expiry = $data['expiry'] ?? null;
            $this->reset_token = $data['reset_token'] ?? null;
            $this->last_login = $data['last_login'] ?? date('Y-m-d H:i:s');
            $this->status = $data['status'] ?? 'active';
            $this->created_by = $data['created_by'] ?? 1;
            $this->updated_by = $data['updated_by'] ?? 1;
            $this->address = $data['address'] ?? null;
            $this->phone = $data['phone'] ?? null;
            $this->date_of_birth = $data['date_of_birth'] ?? null;
            $this->bio = $data['bio'] ?? null;
            $this->website_url = $data['website_url'] ?? null;
            $this->twitter_handle = $data['twitter_handle'] ?? null;
            $this->linkedin_profile = $data['linkedin_profile'] ?? null;
        }
    }

    // Getter ve Setter metodları
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getAvatarUrl() {
        return $this->avatar_url;
    }

    public function setAvatarUrl($avatar_url) {
        $this->avatar_url = $avatar_url;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function getExpiry() {
        return $this->expiry;
    }

    public function setExpiry($expiry) {
        $this->expiry = $expiry;
    }

    public function getResetToken() {
        return $this->reset_token;
    }

    public function setResetToken($reset_token) {
        $this->reset_token = $reset_token;
    }

    public function getLastLogin() {
        return $this->last_login;
    }

    public function setLastLogin($last_login) {
        $this->last_login = $last_login;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getCreatedBy() {
        return $this->created_by;
    }

    public function setCreatedBy($created_by) {
        $this->created_by = $created_by;
    }

    public function getUpdatedBy() {
        return $this->updated_by;
    }

    public function setUpdatedBy($updated_by) {
        $this->updated_by = $updated_by;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getDateOfBirth() {
        return $this->date_of_birth;
    }

    public function setDateOfBirth($date_of_birth) {
        $this->date_of_birth = $date_of_birth;
    }

    public function getBio() {
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = $bio;
    }

    public function getWebsiteUrl() {
        return $this->website_url;
    }

    public function setWebsiteUrl($website_url) {
        $this->website_url = $website_url;
    }

    public function getTwitterHandle() {
        return $this->twitter_handle;
    }

    public function setTwitterHandle($twitter_handle) {
        $this->twitter_handle = $twitter_handle;
    }

    public function getLinkedinProfile() {
        return $this->linkedin_profile;
    }

    public function setLinkedinProfile($linkedin_profile) {
        $this->linkedin_profile = $linkedin_profile;
    }
}