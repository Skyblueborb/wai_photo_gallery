<?php
$title = 'Register';
include 'partials/header.php';
?>
<div class="auth-container">
    <div class="form-wrapper">
        <h1>Register</h1>
        <form action="/register" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="reg_username">Username:</label>
                    <input type="text" id="reg_username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="reg_email">Email:</label>
                    <input type="email" id="reg_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="reg_password">Password:</label>
                    <input type="password" id="reg_password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="reg_password_confirm">Confirm Password:</label>
                    <input type="password" id="reg_password_confirm" name="password_confirm" required>
                </div>
                <div class="form-group">
                    <label for="reg_pfp">Profile Picture:</label>
                    <input type="file" id="reg_pfp" name="profile_picture" accept="image/png, image/jpeg" required>
                </div>
            <button type="submit">Register</button>
        </form>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
