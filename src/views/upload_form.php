<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Form</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;

            flex-direction: column; /* This stacks children vertically */
            align-items: center;    /* This now centers them horizontally */

            padding-top: 5vh;

            background-color: #f4f4f4;
        }

        .upload-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .errors {
            width: 80%;
            max-width: 500px;

            margin-bottom: 1rem;

            padding: 1rem;
            border: 1px solid #d9534f;
            background-color: #f2dede;
            color: #a94442;
            border-radius: 4px;
        }

        .errors p {
            margin: 0;
            padding: 0;
        }

        input[type="file"] {
            margin-bottom: 1rem;
        }

        button {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- This block will now appear above the upload container -->
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="upload-container">
        <h1>Upload an Image</h1>

        <!-- The Form -->
        <form action="/upload" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 1rem; text-align: left;">
                <label for="title_input">Title:</label><br>
                <input type="text" id="title_input" name="title" required style="width: 95%;">
            </div>

            <div style="margin-bottom: 1rem; text-align: left;">
                <label for="author_input">Author:</label><br>
                <input type="text" id="author_input" name="author" required style="width: 95%;">
            </div>

            <div>
                <label for="image_input">Select image:</label>
                <input type="file" id="image_input" name="image_file" accept="image/png, image/jpeg" required>
            </div>

            <br>

            <button type="submit" name="submit">Upload</button>

        </form>
    </div>

</body>
</html>
