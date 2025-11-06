<?php
$title = 'Login';
include 'partials/header.php';
?>

<!-- Message handling for login/register status -->
<?php
    $messageText = null;
    $messageClass = '';
    // ... (your existing message logic from GET params) ...
?>
<?php if ($messageText): ?>
    <div class="message-box <?php echo $messageClass; ?>">
        <p><?php echo htmlspecialchars($messageText); ?></p>
    </div>
<?php endif; ?>

<div class="auth-container">
    <div class="form-wrapper">
        <h1>Login</h1>
        <form action="/login" method="POST">
            <div class="form-group">
                <label for="login_username">Username:</label>
                <input type="text" id="login_username" name="username" required>
            </div>
            <div class="form-group">
                <label for="login_password">Password:</label>
                <input type="password" id="login_password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <a href="/register" class="form-switch-link">Don't have an account? Register here</a>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
