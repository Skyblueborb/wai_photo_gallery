<?php
$title = 'Search Images';
include 'partials/header.php';
?>

<div class="search-page-container">
    <h1>Search for Images by Title</h1>

    <div class="search-box">
        <input type="text" id="search-input" placeholder="">
    </div>

    <div id="results-container" class="gallery-container">
    </div>
</div>

<script>
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('results-container');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();

        if (searchTerm.length < 1) {
            resultsContainer.innerHTML = '';
            return;
        }

        fetch(`/search/ajax?q=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';

                if (data.length === 0) {
                    resultsContainer.innerHTML = '<p class="no-images">No images found.</p>';
                    return;
                }

                data.forEach(image => {
                    const itemHtml = `
                        <div class="gallery-item">
                            <a href="${image.original}" target="_blank">
                                <img src="${image.thumb}" alt="Thumbnail">
                            </a>
                            <div class="gallery-item-info">
                                <a>Title: ${image.metadata.title}</a>
                                <a>Author: ${image.metadata.author}</a>
                                <a>Visibility: ${image.metadata.type}</a>
                            </div>
                        </div>
                    `;
                    resultsContainer.innerHTML += itemHtml;
                });
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                resultsContainer.innerHTML = '<p class="no-images">An error occurred.</p>';
            });
    });
</script>

<?php include 'partials/footer.php'; ?>
