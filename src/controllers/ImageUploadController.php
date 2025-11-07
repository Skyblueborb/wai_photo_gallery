<?php

require_once BASE_PATH . 'controllers/BaseController.php';

require_once BASE_PATH . 'models/Image.php';


class ImageUploadController extends BaseController {
    public function showForm() {
        $this->render('upload_form', ['errors' => $this->errors]);
    }

    public function handleUpload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['submit'])) {
                if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['image_file'];
                    $metadata = [
                        'title' => $_POST['title'] ?? 'Untitled',
                        'author' => $_POST['author'] ?? 'Unknown',
                        'type' => $_POST['type'] ?? 'Unknown'
                    ];

                    if (!$this->isAuthenticated() && $metadata['type'] == 'private') {
                        $this->redirect('/upload', ['errors' => ['Registered users are only allowed to upload private images.']]);
                    }

                    $image = new ImageModel;

                    if($image->save($file, $metadata)) {
                        $this->redirect('/', ['success' => ['upload_success' => 'Your image was uploaded successfully'], 'errors' => $this->errors]);
                    } else {
                        $this->errors = $image->getErrors();
                        $this->redirect('/upload', ['errors' => $this->errors]);
                    }
                }
            } else {
                $errors = ['upload' => 'There was a problem with the upload. Please try again.'];
                $this->redirect('/upload', ['errors' => $errors]);
            }
        }
    }
}
