<?php

require_once BASE_PATH . 'controllers/BaseController.php';
require_once BASE_PATH . 'models/Image.php';

class GalleryController extends BaseController {

    public function index() {
        $imagesPerPage = 6;

        // Ensure page is always positive
        $currentPage = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;


        $imageData = ImageModel::getAll($currentPage, $imagesPerPage);

        $images = $imageData['images'];
        $totalPages = $imageData['totalPages'];

        $this->render('gallery', [
            'images' => $images,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }
}
