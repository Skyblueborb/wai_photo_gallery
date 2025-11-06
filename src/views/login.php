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
    <!-- Login Form -->
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
    </div>
</body>
</html>
