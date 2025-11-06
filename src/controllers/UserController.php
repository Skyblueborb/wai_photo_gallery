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
        $this->render('login', ['errors' => $this->errors]);
    }

    public function showRegister() {
        $this->render('register', ['errors' => $this->errors]);
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->create($_POST, $_FILES)) {
                $this->redirect('/login', ['status' => 'success', 'code' => 'reg_success']);
            } else {
                $this->errors = $this->userModel->getErrors();
                $this->showRegister();
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
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile_picture'] = DIRECTORY_SEPARATOR . 'ProfilesFoto' . DIRECTORY_SEPARATOR . $user['profile_picture'];

                $this->redirect('/');
            } else {
                $this->errors = $this->userModel->getErrors();
                $this->showLogin();
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
