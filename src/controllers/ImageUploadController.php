<?php

require_once BASE_PATH . 'controllers/BaseController.php';

require_once BASE_PATH . 'models/Image.php';


class ImageUploadController extends BaseController {
    public function showForm() {
        $this->render('upload_form', ['errors'  => []]);
    }

    public function showGallery() {
        $this->render('gallery');
    }

    public function handleUpload() {
        if (isset($_POST['submit'])) {
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image_file'];
                $image = new ImageModel;

                if($image->save($file)) {
                    header("Location: /");
                    exit;
                } else {
                    $errors = $image->getErrors();
                    $this->render('upload_form', ['errors' => $errors]);
                }
            }
        } else {
                $errors = ['upload' => 'There was a problem with the upload. Please try again.'];
                $this->render('upload_form', ['errors' => $errors]);
        }
    }
}
