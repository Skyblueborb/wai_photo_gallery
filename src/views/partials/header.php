<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Image Gallery'; ?></title>
    <link rel="stylesheet" href="/static/styles.css">
</head>
<body>

<div class="floating-message-container">
    <?php if (!empty($messages['success'])): ?>
        <?php foreach ($messages['success'] as $message): ?>
            <div class="floating-message-box success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($messages['error'])): ?>
        <?php foreach ($messages['error'] as $message): ?>
            <div class="floating-message-box error">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'nav.php'; ?>
<div class="container">
