<?php

class BaseController
{
    protected $errors = [];

    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    protected function render($view_name, $data = [])
    {
        $statusMessages = [
            'success' => [
                'reg_success' => 'Registration successful! You can now log in.',
                'upload_success' => 'Your image was uploaded successfully!',
            ],
            'error' => [
                'default' => 'An unknown error occurred.'
            ]
        ];
        $messages = ['success' => [], 'error' => []];

        if (isset($_GET['status']) && isset($_GET['code'])) {
            $status = $_GET['status'];
            $code = $_GET['code'];
            if (isset($statusMessages[$status][$code])) {
                $messages[$status][] = $statusMessages[$status][$code];
            } else {
                $messages['error'][] = $statusMessages['error']['default'];
            }
        }

        if (isset($data['errors']) && is_array($data['errors'])) {
            $messages['error'] = array_merge($messages['error'], $data['errors']);
            unset($data['errors']);
        }

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
