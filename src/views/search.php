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
        <!-- Search results will be dynamically inserted here -->
    </div>
</div>

<script>
    // Get references to the HTML elements
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('results-container');

    // Listen for the 'input' event on the search box
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();

        if (searchTerm.length < 1) {
            resultsContainer.innerHTML = '';
            return;
        }

        // Use the Fetch API to send a request to our server
        fetch(`/search/ajax?q=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json()) // Parse the JSON response
            .then(data => {
                // Clear any previous results
                resultsContainer.innerHTML = '';

                // If no results were returned, show a message
                if (data.length === 0) {
                    resultsContainer.innerHTML = '<p class="no-images">No images found.</p>';
                    return;
                }

                // Loop through the results and build the HTML for each image
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
