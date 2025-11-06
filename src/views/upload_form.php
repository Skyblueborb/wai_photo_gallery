<?php
$title = 'Upload Image';
include 'partials/header.php';
?>

<div class="form-container">
    <h1>Upload an Image</h1>

    <?php if (!empty($errors)): ?>
        <div class="message-box error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/upload" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title_input">Title:</label>
            <input type="text" id="title_input" name="title" required>
        </div>
        <div class="form-group">
            <label for="author_input">Author:</label>
            <input type="text" id="author_input" name="author" required>
        </div>
        <div class="form-group">
            <label for="image_input">Select image:</label>
            <input type="file" id="image_input" name="image_file" accept="image/png, image/jpeg" required>
        </div>
        <button type="submit">Upload</button>
    </form>
</div>

<?php include 'partials/footer.php'; ?>
