<nav>
    <div class="nav-left">
        <a href="/" style="text-decoration: none; color: inherit; font-weight: bold; font-size: 1.2rem;">Gallery</a>
        <a href="/upload" class="nav-button">Upload</a>
    </div>
    <div class="nav-right">
        <?php if ($isLoggedIn && $currentUser): ?>
            <div class="nav-profile">
                <?php if (!empty($currentUser['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($currentUser['profile_picture']); ?>" alt="Profile Picture">
                <?php endif; ?>
                <span><?php echo htmlspecialchars($currentUser['username']); ?></span>
            </div>
            <a href="/logout" class="nav-button">Logout</a>
        <?php else: ?>
            <a href="/login" class="nav-button">Login / Register</a>
        <?php endif; ?>
    </div>
</nav>
