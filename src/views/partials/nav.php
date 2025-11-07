<nav>
    <div class="nav-left">
        <a href="/" style="text-decoration: none; color: inherit; font-weight: bold; font-size: 1.2rem;">Gallery</a>
        <a href="/upload" class="nav-button">Upload</a>
        <a href="/saved" class="nav-button">Saved photos</a>
        <a href="/search" class="nav-button">Search</a>
    </div>
    <div class="nav-right">
        <?php if ($isLoggedIn): ?>
            <div class="nav-profile">
                <span><?php echo htmlspecialchars($username); ?></span>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
            </div>
            <a href="/logout" class="nav-button">Logout</a>
        <?php else: ?>
            <a href="/login" class="nav-button">Login / Register</a>
        <?php endif; ?>
    </div>
</nav>
