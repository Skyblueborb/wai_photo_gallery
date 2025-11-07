<?php

require_once BASE_PATH . 'controllers/BaseController.php';
require_once BASE_PATH . 'models/Image.php';

class GalleryController extends BaseController {


    public function showGallery() {
        $galleryData = $this->prepareGalleryData(false);

        $this->render('gallery', $galleryData);
    }

    public function showSaved() {
        $galleryData = $this->prepareGalleryData(true);

        $this->render('saved', $galleryData);
    }

    private function prepareGalleryData($filterBySaved = false) {
        $imagesPerPage = 5;

        $currentPage = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $saved_images = $_SESSION['saved_images'] ?? [];

        $filterFolders = null;
        if ($filterBySaved) {
            $filterFolders = array_keys($saved_images);

            if (empty($filterFolders)) {
                return [
                    'images' => [],
                    'currentPage' => 1,
                    'totalPages' => 0,
                    'saved_images' => [],
                    'totalPhotos' => 0
                ];
            }
        }

        $imageData = ImageModel::getAll($currentPage, $imagesPerPage, $filterFolders);

        $totalPhotos = array_sum($saved_images);

        return [
            'images' => $imageData['images'],
            'currentPage' => $currentPage,
            'totalPages' => $imageData['totalPages'],
            'saved_images' => $saved_images,
            'totalPhotos' => $totalPhotos
        ];
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
