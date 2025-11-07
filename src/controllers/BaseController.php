<?php

require_once BASE_PATH . 'models/DatabaseUtils.php';

class BaseController
{
    protected $errors = [];

    protected function isAuthenticated() {
        if(!isset($_SESSION['username'], $_SESSION['session_token'])) return false;
        $user = DatabaseUtils::getUser($_SESSION['username']);
        if(!$user) return false;

        $expected_hash = hash('sha256', $user['password'] . session_id());

        if(hash_equals($expected_hash, $_SESSION['session_token'])) {
            return true;
        } else {
            $cookieParams = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $cookieParams['path'],
                $cookieParams['domain'],
                $cookieParams['secure'],
                $cookieParams['httponly']
            );
            session_destroy();
            return false;
        }
    }

    protected function render($view_name, $data = [])
    {
        $messages = ['success' => [], 'errors' => []];

        if (isset($_SESSION['flash_messages'])) {
            $messages = array_merge_recursive($messages, $_SESSION['flash_messages']);
            unset($_SESSION['flash_messages']);
        }

        if (isset($data['errors']) && is_array($data['errors'])) {
            $messages['errors'] = array_merge($messages['errors'], $data['errors']);
            unset($data['errors']);
        }

        $data['isLoggedIn'] = $this->isAuthenticated();
        $data['username'] = $_SESSION['username'] ?? null;
        $data['profile_picture'] = $_SESSION['profile_picture'] ?? null;
        extract($data);
        include BASE_PATH . 'views/' . $view_name . '.php';
    }

    protected function redirect($url, $flashMessages = []) {
        if (!empty($flashMessages)) {
            $_SESSION['flash_messages'] = $flashMessages;
        }

        header("Location: " . $url);
        exit;
    }
}
