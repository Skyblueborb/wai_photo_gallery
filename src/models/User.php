<?php

require_once BASE_PATH . 'models/MongoDB.php'; // Use the provided MongoDB connection class
require_once BASE_PATH . 'models/Image.php'; // Use the provided MongoDB connection class

// Define the path for profile pictures
define('PFP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ProfilesFoto');

class UserModel {
    private $errors = [];
    private $db;
    private $collection;

    public function __construct() {
        // Get the database connection when the model is created
        $this->db = MongoDB::getInstance()->getDatabase();
        $this->collection = $this->db->users; // Select the 'users' collection
    }

    public function create($data, $file) {
        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $profile_picture = $file['profile_picture'] ?? null;

        if (empty($username) || empty($email) || empty($password) || empty($profile_picture)) {
            $this->errors['fields'] = 'All fields are required.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'A valid email address is required.';
        }

        if ($this->collection->findOne(['username' => $username])) {
            $this->errors['username'] = 'This username is already taken.';
        }
        if ($this->collection->findOne(['email' => $email])) {
            $this->errors['email_taken'] = 'An account with this email already exists.';
        }

        if (!empty($this->errors)) {
            return false;
        }

        if (isset($file['profile_picture']) && $file['profile_picture']['error'] === UPLOAD_ERR_OK) {

            if (!is_dir(PFP_PATH)) {
                mkdir(PFP_PATH, 0755, true);
            }

            $timestamp = time();

            $file_name = basename($profile_picture['name']);
            $filename_without_ext = pathinfo($file_name, PATHINFO_FILENAME);

            $pfp_filename = $timestamp . '_' . $filename_without_ext;
            $destination = PFP_PATH . DIRECTORY_SEPARATOR . $timestamp . '_'. $file_name;
            $timestamp_filename = $timestamp . '_' . $file_name;

            $tmp_filename = $profile_picture['tmp_name'];

            $image = new ImageModel();
            $image->createThumbnail($tmp_filename, $tmp_filename, 64, 64);

            if (!move_uploaded_file($profile_picture['tmp_name'], $destination)) {
                $this->errors['pfp'] = 'Could not save the profile picture.';
                return false;
            }
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $userDocument = [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'profile_picture' => $timestamp_filename,
            ];

            $this->collection->insertOne($userDocument);

        } catch (Exception $e) {
            $this->errors['database'] = 'A database error occurred during registration.';
            return false;
        }

        return true; // Success!
    }

    public function verifyCredentials($username, $password) {
        try {
            // Find the user by their username
            $user = $this->collection->findOne(['username' => $username]);

            // 1. Check if a user was found
            if (!$user) {
                return false;
            }

            // 2. Verify the provided password against the stored hash
            if (password_verify($password, $user['password'])) {
                // Password is correct, return the user data
                return $user;
            }

            // Password was incorrect
            return false;

        } catch (Exception $e) {
            $this->errors['database'] = 'A database error occurred during login.';
            return false;
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
