<?php

require_once BASE_PATH . 'controllers/BaseController.php';
require_once BASE_PATH . 'models/Image.php';

class GalleryController extends BaseController {

    public function showGallery() {
        $imagesPerPage = 6;

        // Ensure page is always positive
        $currentPage = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;


        $imageData = ImageModel::getAll($currentPage, $imagesPerPage);

        if(isset($_SESSION['saved_images'])) {
            $saved_images = $_SESSION['saved_images'];
        } else {
            $_SESSION['saved_images'] = [];
            $saved_images = [];
        }

        $images = $imageData['images'];
        $totalPages = $imageData['totalPages'];
        $totalPhotos = array_sum($_SESSION['saved_images']);

        $this->render('gallery', [
            'images' => $images,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'saved_images' => $saved_images,
            'totalPhotos' => $totalPhotos
        ]);
    }

    public function showSaved() {
        $imagesPerPage = 6;

        // Ensure page is always positive
        $currentPage = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        if(isset($_SESSION['saved_images'])) {
            $saved_images = $_SESSION['saved_images'];
        } else {
            $_SESSION['saved_images'] = [];
            $saved_images = [];
        }

        $filterFolders = array_keys($_SESSION['saved_images']);
        $totalPhotos = array_sum($_SESSION['saved_images']);

        $imageData = ImageModel::getAll($currentPage, $imagesPerPage, $filterFolders);

        $images = $imageData['images'];
        $totalPages = $imageData['totalPages'];

        $this->render('saved', [
            'images' => $images,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'saved_images' => $saved_images,
            'totalPhotos' => $totalPhotos
        ]);
    }

    public function handleSavedForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
            $selectedFolders = $_POST['selected_images'] ?? [];
            $quantities = $_POST['quantity'] ?? [];

            switch ($action) {
                case 'update':
                    foreach ($selectedFolders as $folder) {
                        $quantity = isset($quantities[$folder]) && (int)$quantities[$folder] > 0 ? (int)$quantities[$folder] : 1;
                        $_SESSION['saved_images'][$folder] = $quantity;
                    }
                break;
            case 'remove':
                foreach ($selectedFolders as $folder) {
                    unset($_SESSION['saved_images'][$folder]);
                }
                break;
        }
    }
    $this->redirect('/saved');
    }
}
