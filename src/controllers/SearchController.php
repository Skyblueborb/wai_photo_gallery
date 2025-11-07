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
            $subdir = $doc['folder'];
            $orig_name = $subdir . '.' . $doc['extension'];
            $thumb_name = $subdir . '_thumb.' . $doc['extension'];

            $orig_path = DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $orig_name;
            $thumb_path = DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $thumb_name;

            $metadata = [
                'author' => $doc['author'],
                'title' => $doc['title'],
                'type' => $doc['type']
            ];

            $formattedResults[] = [
                'original' => $orig_path,
                'thumb' => $thumb_path,
                'metadata' => $metadata
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($formattedResults);
        exit;
    }
}
