<?php

class BaseController
{
    protected $errors = [];

    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    protected function render($view_name, $data = [])
    {
        $data['isLoggedIn'] = $this->isAuthenticated();
        $data['username'] = $_SESSION['username'] ?? null;
        $data['profile_picture'] = $_SESSION['profile_picture'] ?? null;
        extract($data);
        include BASE_PATH . 'views/' . $view_name . '.php';
    }

    protected function redirect($url, $queryParams = []) {
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        header("Location: " . $url);
        exit;
    }
}
