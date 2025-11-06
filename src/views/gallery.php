<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 2rem; }
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem; /* Add space above pagination */
        }
        .gallery-item a { display: block; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .gallery-item img { width: 100%; height: 125px; object-fit: cover; display: block; transition: transform 0.2s ease-in-out; }
        .gallery-item a:hover img { transform: scale(1.05); }
        .no-images { text-align: center; color: #777; font-size: 1.2rem; }
        h1 { text-align: center; }
        nav { text-align: center; margin-bottom: 2rem; }
        nav a { padding: 0.5rem 1rem; text-decoration: none; background: #007bff; color: white; border-radius: 4px; }

        /* --- NEW PAGINATION STYLES --- */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #007bff;
            background-color: white;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        .pagination span.current {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
            font-weight: bold;
        }
        .pagination span.disabled {
            color: #ccc;
            background-color: #f8f8f8;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <nav>
        <a href="/upload">Upload a New Image</a>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    </nav>
    <?php if ($isLoggedIn): ?>
        <span>Welcome, <?php echo htmlspecialchars($username); ?>!</span>
        <img src="<?php echo htmlspecialchars($profile_picture); ?>"/>
        <a href="/upload">Upload Image</a>
        <a href="/logout">Logout</a>
    <?php else: ?>
        <a href="/login">Login / Register</a>
    <?php endif; ?>
    <h1>Image Gallery</h1>

    <div class="gallery-container">
        <!-- The image display logic is the same -->
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

    <!-- --- NEW PAGINATION CONTROLS --- -->
    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <!-- "Previous" Link -->
            <?php if ($currentPage > 1): ?>
                <a href="/?page=<?php echo $currentPage - 1; ?>">&laquo; Previous</a>
            <?php else: ?>
                <span class="disabled">&laquo; Previous</span>
            <?php endif; ?>

            <!-- Page Number Links -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="/?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- "Next" Link -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="/?page=<?php echo $currentPage + 1; ?>">Next &raquo;</a>
            <?php else: ?>
                <span class="disabled">Next &raquo;</span>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</body>
</html>
