<?php
$tittle = 'Gallery';
include 'partials/header.php';
?>
    <h1>Image Gallery</h1>

    <div class="gallery-container">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $image): ?>
                <div class="gallery-item">
                    <a href="<?php echo htmlspecialchars($image['original']); ?>" target="_blank">
                        <img src="<?php echo htmlspecialchars($image['thumb']); ?>" alt="Thumbnail">
                    </a>
                    <a> Title: <?php echo htmlspecialchars($image['metadata']['title']); ?> </a>
                    <a> Author: <?php echo htmlspecialchars($image['metadata']['author']); ?> </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-images">No images have been uploaded yet.</p>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php if ($currentPage > 1): ?>
                <a href="/?page=<?php echo $currentPage - 1; ?>">&laquo; Previous</a>
            <?php else: ?>
                <span class="disabled">&laquo; Previous</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="/?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="/?page=<?php echo $currentPage + 1; ?>">Next &raquo;</a>
            <?php else: ?>
                <span class="disabled">Next &raquo;</span>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php include 'partials/footer.php'; ?>
