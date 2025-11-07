<?php
$tittle = 'Gallery';
include 'partials/header.php';
?>
    <h1>Saved photos</h1>

<?php include 'partials/savedtotal.php'; ?>

    <button type="submit" name="action" value="remove" form="gallery-selection-form" class="nav-button" style="margin-bottom: 2rem;">Remove saved</button>
    <button type="submit" name="action" value="update" form="gallery-selection-form" class="nav-button" style="margin-bottom: 2rem;">Update quantity</button>

    <form action="/save" method="POST" id="gallery-selection-form">
    <div class="gallery-container">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $image): ?>
                <div class="gallery-item">
                    <a href="<?php echo htmlspecialchars($image['original']); ?>" target="_blank">
                        <img src="<?php echo htmlspecialchars($image['thumb']); ?>" alt="Thumbnail">
                    </a>
                    <a> Title: <?php echo htmlspecialchars($image['metadata']['title']); ?> </a>
                    <a> Author: <?php echo htmlspecialchars($image['metadata']['author']); ?> </a>
                    <a> Visibility: <?php echo htmlspecialchars($image['metadata']['type']); ?> </a>
                    <input type="checkbox" name="selected_images[]" value="<?php echo htmlspecialchars($image['id']); ?>">
                    <input type="number" name="quantity[<?php echo htmlspecialchars($image['id']); ?>]" value="<?php echo htmlspecialchars($saved_images[$image['id']] ?? 1); ?>" min="1" max="99" style="width: 50px;">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-images">No images have been uploaded yet.</p>
        <?php endif; ?>
    </div>
    </form>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php if ($currentPage > 1): ?>
                <a href="/saved?page=<?php echo $currentPage - 1; ?>">&laquo; Previous</a>
            <?php else: ?>
                <span class="disabled">&laquo; Previous</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="/saved?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="/saved?page=<?php echo $currentPage + 1; ?>">Next &raquo;</a>
            <?php else: ?>
                <span class="disabled">Next &raquo;</span>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php include 'partials/footer.php'; ?>
