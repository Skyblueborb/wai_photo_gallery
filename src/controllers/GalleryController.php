<?php

require_once BASE_PATH . 'controllers/BaseController.php';
require_once BASE_PATH . 'models/Image.php';

class GalleryController extends BaseController {

    public function index() {
        $imagesPerPage = 4;

        // Get the current page number from the URL, defaulting to page 1
        // Sanitize it to be a positive integer.
        $currentPage = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $imageModel = new ImageModel();

        // Get the paginated data from the model
        $imageData = $imageModel->getAll($currentPage, $imagesPerPage);

        $images = $imageData['images'];
        $totalImages = $imageData['total'];

        // Calculate the total number of pages needed
        $totalPages = ceil($totalImages / $imagesPerPage);

        // Pass all the necessary data to the view
        $this->render('gallery', [
            'images' => $images,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }
}
