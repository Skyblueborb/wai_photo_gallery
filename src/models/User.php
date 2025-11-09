<?php

require_once BASE_PATH . 'models/MongoDB.php';
require_once BASE_PATH . 'models/Image.php';
require_once BASE_PATH . 'models/DatabaseUtils.php';

define('PFP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ProfilesFoto');
define('USERS_COLLECTION', 'users');


class UserModel {
    private $errors = [];

    public function create($data, $file) {
        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $passwordConfirm = $data['password_confirm'] ?? null;
        $profile_picture = $file['profile_picture'] ?? null;

        if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm) || empty($profile_picture)) {
            $this->errors['fields'] = 'All fields are required.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'A valid email address is required.';
        }
        if ($password !== $passwordConfirm) {
            $this->errors['password_match'] = 'The passwords do not match.';
        }

        if (DataBaseUtils::findOne('username', $username, USERS_COLLECTION)) {
            $this->errors['username'] = 'This username is already taken.';
        }
        if (DatabaseUtils::findOne('email', $email , USERS_COLLECTION)) {
            $this->errors['email_taken'] = 'An account with this email already exists.';
        }

        if (!empty($this->errors)) {
            return false;
        }

        if (isset($file['profile_picture']) && $file['profile_picture']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir(PFP_PATH)) {
                mkdir(PFP_PATH, 0755, true);
            }

            $filesize = $file['profile_picture']['size'];
            if($filesize > ONE_MB) {
                $this->errors['size'] = 'Filesize is too big, limit is 1MB.';
                return false;
            }

            $timestamp = time();

            $file_name = basename($profile_picture['name']);
            $timestamp_filename = $timestamp . '_' . $file_name;

            $destination = PFP_PATH . DIRECTORY_SEPARATOR . $timestamp_filename;

            $tmp_filename = $profile_picture['tmp_name'];

            ImageModel::createThumbnail($tmp_filename, $tmp_filename, 64, 64);

            if (!move_uploaded_file($tmp_filename, $destination)) {
                $this->errors['pfp'] = 'Could not save the profile picture.';
                return false;
            }
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $document = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'profile_picture' => $timestamp_filename,
        ];

        if (!DatabaseUtils::saveDocument($document, 'users')) {
            $this->errors['database'] = 'Failed to save image metadata.';
            return false;
        }

        return true;
    }

    public function verifyCredentials($username, $password) {
        $user = DatabaseUtils::getUser($username);
        if(!$user) {
            $this->errors['username'] = 'This username does not exist.';
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            $this->errors['password'] = 'Incorrect password.';
            return false;
        }
        return $user;
    }

    public function getErrors() {
        return $this->errors;
    }
}
