<?php

require_once BASE_PATH . 'controllers/BaseController.php';

require_once BASE_PATH . 'models/MongoDB.php';
require_once BASE_PATH . 'models/User.php';

class UserController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLogin() {
        if($this->isAuthenticated()) $this->redirect('/');
        $this->render('login', ['errors' => $this->errors]);
    }

    public function showRegister() {
        if($this->isAuthenticated()) $this->redirect('/');
        $this->render('register', ['errors' => $this->errors]);
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->create($_POST, $_FILES)) {
                $this->redirect('/login', ['success' => ['reg_success' => 'Registration successful! You can now log in.'], 'errors' => $this->errors]);
            } else {
                $this->errors = $this->userModel->getErrors();
                $this->redirect('/register', ['errors' => $this->errors]);
            }
        } else {
            $this->redirect('/register');
        }
    }

    public function handlelogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->verifyCredentials($username, $password);

            if ($user) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = (string)$user['_id'];
                $_SESSION['session_token'] = hash('sha256', $user['password'] . session_id());
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile_picture'] = DIRECTORY_SEPARATOR . 'ProfilesFoto' . DIRECTORY_SEPARATOR . $user['profile_picture'];

                $this->redirect('/');
            } else {
                $this->errors = $this->userModel->getErrors();
                $this->redirect('/login', ['errors' => $this->errors]);
            }
        }
        exit;
    }

    public function logout() {
        session_unset();

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
        $this->redirect('/');
    }

}
