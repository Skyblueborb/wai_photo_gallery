<?php

require_once BASE_PATH . 'controllers/BaseController.php';
require_once BASE_PATH . 'models/DatabaseUtils.php';
require_once BASE_PATH . 'models/Image.php';

class SearchController extends BaseController {

    public function showSearchPage() {
        $this->render('search');
    }

    public function ajaxSearch() {
        $searchTerm = $_GET['q'] ?? '';

        $username = $_SESSION['username'] ?? null;

        $rawResults = DatabaseUtils::searchImagesByTitle($searchTerm, $username);

        $formattedResults = [];
        foreach ($rawResults as $doc) {
            $folder = $doc['folder'];
            $image = ImageModel::getAll(1,1,[$folder])['images'][0];
            $formattedResults[] = [
                'original' => $image['original'],
                'thumb' => $image['thumb'],
                'metadata' => $image['metadata']
            ];
        }

        // Set the content type header to JSON and echo the encoded results
        header('Content-Type: application/json');
        echo json_encode($formattedResults);
        exit; // Terminate the script
    }
}
