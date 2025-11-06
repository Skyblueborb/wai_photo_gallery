<?php

require_once BASE_PATH . 'controllers/BaseController.php';

require_once BASE_PATH . 'models/MongoDB.php';
require_once BASE_PATH . 'models/User.php';

class UserController extends BaseController {

    /**
     * Displays the combined login and registration form page.
     */
    public function showLogin() {
        $this->render('login', ['errors' => []]);
    }

    public function showRegister() {
        $this->render('register', ['errors' => []]);
    }

    public function handleRegister() {
        // Ensure this is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userModel = new UserModel();

            // Pass the POST data and FILES data to the model's create method
            if ($userModel->create($_POST, $_FILES)) {
                // Success: Redirect to the login page with a success message
                header("Location: /login?status=reg_success");
                exit;
            } else {
                // Failure: Redirect back with an error code
                $errors = $userModel->getErrors();
                $errorCode = array_key_first($errors) ?? 'unknown'; // Get the first error key
                header("Location: /login?status=reg_error&code=" . urlencode($errorCode));
                exit;
            }
        } else {
            // If not a POST request, just redirect to the form
            header("Location: /register");
            exit;
        }
    }

    public function handlelogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();

            // The model method returns the user document on success, or false on failure
            $user = $userModel->verifyCredentials($username, $password);

            if ($user) {
                // --- LOGIN SUCCESSFUL ---

                // 1. Regenerate session ID to prevent session fixation attacks (VERY IMPORTANT)
                session_regenerate_id(true);

                // 2. Store user identifiers in the session
                $_SESSION['user_id'] = (string)$user['_id']; // Cast MongoDB's ObjectId to a string
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile_picture'] = DIRECTORY_SEPARATOR . 'ProfilesFoto' . DIRECTORY_SEPARATOR . $user['profile_picture'];

                // 3. Redirect to a protected page, like the gallery
                header("Location: /");
                exit;
            } else {
                // --- LOGIN FAILED ---
                // Redirect back to the login form with an error
                header("Location: /login?status=login_error");
                exit;
            }
        }
        header("Location: /login"); // Redirect if not a POST request
        exit;
    }

    public function logout() {
        session_unset();    // Unset all session variables

        $cookieParams = session_get_cookie_params();
        setcookie(
            session_name(),      // The name of the session cookie (e.g., 'PHPSESSID')
            '',                  // An empty value
            time() - 42000,      // A timestamp in the past (e.g., one hour ago)
            $cookieParams['path'],
            $cookieParams['domain'],
            $cookieParams['secure'],
            $cookieParams['httponly']
        );

        session_destroy();  // Destroy the session
        header("Location: /"); // Redirect to the home page
        exit;
    }

}
