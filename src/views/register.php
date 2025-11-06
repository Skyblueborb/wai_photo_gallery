<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 2rem; display: flex; justify-content: center; align-items: center; min-height: 90vh; }
        .auth-container {
            display: flex;
            gap: 2rem;
            width: 100%;
            max-width: 800px;
        }
        .form-wrapper {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 50%;
        }
        .form-wrapper h1 { text-align: center; margin-top: 0; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; }
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="email"],
        .form-group input[type="file"] {
            width: 95%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <div class="auth-container">

        <!-- Registration Form -->
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
                    <label for="reg_pfp">Profile Picture:</label>
                    <input type="file" id="reg_pfp" name="profile_picture" accept="image/png, image/jpeg" required>
                </div>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
