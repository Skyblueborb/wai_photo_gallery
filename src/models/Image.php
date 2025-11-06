<?php

define('ONE_MB', 1024 * 1024);

define('IMAGES_PATH', BASE_PATH .  DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR .'images');

define("THUMB_WIDTH", 200);
define("THUMB_HEIGHT", 125);

require_once BASE_PATH . 'models/MongoDB.php';

class ImageModel {
    private $errors = [];

    public function save($image, $metadata) {
        $file_name = basename($image['name']);
        $mimetype = $image['type'];
        $allowed_extensions = ['jpg', 'png'];
        $allowed_mimetypes = ['image/png', 'image/jpeg'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if(!in_array($file_extension, $allowed_extensions) || !in_array($mimetype, $allowed_mimetypes)) {
            $this->errors['filetype'] = 'Disallowed filetype, you can only upload png/jpg.';
        }

        $filesize = $image['size'];
        if($filesize > ONE_MB) {
            $this->errors['size'] = 'Filesize is too big, limit is 1MB.';
        }

        if (!empty($this->errors)) {
            return false;
        }


        $timestamp = time();
        $filename_without_ext = pathinfo($file_name, PATHINFO_FILENAME);
        $subdir_path = $timestamp . '_' . $filename_without_ext;

        $target_dir = IMAGES_PATH . DIRECTORY_SEPARATOR . $subdir_path;

        $destination_path = $target_dir . DIRECTORY_SEPARATOR . $file_name;

        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0755, true)) {
                $this->errors['filesystem'] = 'Failed to create image directory.';
                return false;
            }
        }

        $tmp_path = $image['tmp_name'];

        if (!move_uploaded_file($tmp_path, $destination_path)) {
            $this->errors['move'] = 'Could not move the uploaded file.';
            return false;
        }


        $thumb_filename = $filename_without_ext . '_thumb.' . $file_extension;
        $thumb_destination_path = $target_dir . DIRECTORY_SEPARATOR . $thumb_filename;

        $thumb_success = $this->createThumbnail(
            $destination_path,
            $thumb_destination_path,
        );

        if (!$thumb_success) {
            $this->errors['thumbnail'] = 'Failed to create the image thumbnail.';
            return false;
        }

        try {
            // Get the MongoDB database connection
            $db = MongoDB::getInstance()->getDatabase();

            // Select the collection (like a table in SQL). MongoDB creates it if it doesn't exist.
            $collection = $db->images;

            // Prepare the document to be inserted
            $document = [
                'title' => $metadata['title'],
                'author' => $metadata['author'],
                'folder' => $subdir_path, // The unique folder name for this image
            ];

            // Insert the document into the collection
            $insertOneResult = $collection->insertOne($document);

            // Optional: Check if the insert was acknowledged by the server
            if ($insertOneResult->getInsertedCount() !== 1) {
                // This is an unlikely but possible error state
                $this->errors['database'] = 'Failed to save image metadata to the database.';
                // Clean up by deleting the files you just created
                // ... (cleanup logic would go here) ...
                return false;
            }

        } catch (Exception $e) {
            // If anything goes wrong with the database connection or insert
            $this->errors['database'] = 'A database error occurred.';
            // Clean up files here as well
            return false;
        }


        return true;
    }

    public function createThumbnail($source_path, $destination_path, $width=THUMB_WIDTH, $height=THUMB_HEIGHT) {
        list($source_width, $source_height, $source_type) = getimagesize($source_path);

        switch ($source_type) {
            case IMAGETYPE_JPEG:
                $source_image = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_image = imagecreatefrompng($source_path);
                break;
            default:
                return false;
        }

        if (!$source_image) {
            return false;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $thumb_aspect_ratio = $width / $height;

        $crop_rect = ['x' => 0, 'y' => 0, 'width' => $source_width, 'height' => $source_height];

        if ($source_aspect_ratio > $thumb_aspect_ratio) {
            // Original is wider. Crop the sides.
            $crop_rect['width'] = (int)($source_height * $thumb_aspect_ratio);
            $crop_rect['x'] = (int)(($source_width - $crop_rect['width']) / 2);
        } else {
            // Original is taller. Crop the top and bottom.
            $crop_rect['height'] = (int)($source_width / $thumb_aspect_ratio);
            $crop_rect['y'] = (int)(($source_height - $crop_rect['height']) / 2);
        }

        $cropped_image = imagecrop($source_image, $crop_rect);

        if (!$cropped_image) {
            imagedestroy($source_image);
            return false;
        }

        $thumb_image = imagecreatetruecolor($width, $height);

        if ($source_type == IMAGETYPE_PNG) {
            imagealphablending($thumb_image, false);
            imagesavealpha($thumb_image, true);
        }

        imagecopyresampled(
            $thumb_image, $cropped_image,
            0, 0, 0, 0,
            $width, $height,
            $crop_rect['width'], $crop_rect['height']
        );

        $success = false;
        switch ($source_type) {
            case IMAGETYPE_JPEG:
                $success = imagejpeg($thumb_image, $destination_path, 90);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($thumb_image, $destination_path);
                break;
        }

        imagedestroy($source_image);
        imagedestroy($cropped_image);
        imagedestroy($thumb_image);

        return $success;
    }

    private function findByFolder($folderName) {
        try {
            // Get the MongoDB database connection
            $db = MongoDB::getInstance()->getDatabase();
            $collection = $db->images;

            // Define the filter to find the document where the 'folder' field matches
            $filter = ['folder' => $folderName];

            // Use findOne() to get a single document. It returns null if not found.
            $document = $collection->findOne($filter);

            return $document;

        } catch (Exception $e) {
            // If the database connection fails, return null
            $this->errors['database'] = 'A database error occurred.';
            return null;
        }
    }

    public function getAll($page = 1, $perPage = 4) {
        $allImages = [];

        if (!is_dir(IMAGES_PATH)) {
            return ['images' => [], 'total' => 0];
        }

        $iterator = new DirectoryIterator(IMAGES_PATH);

        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $subdirectory_path = $fileinfo->getPathname();
                $subdirectory_name = $fileinfo->getFilename();
                $original_file = null;
                $thumb_file = null;

                $sub_iterator = new DirectoryIterator($subdirectory_path);
                foreach ($sub_iterator as $image_file_info) {
                    if ($image_file_info->isFile()) {
                        $filename = $image_file_info->getFilename();
                        $metadata = $this->findByFolder($subdirectory_name);
                        if (strpos($filename, '_thumb.') !== false) {
                            $thumb_file = $filename;
                        } else {
                            $original_file = $filename;
                        }
                    }
                }

                if ($original_file && $thumb_file) {
                    $allImages[] = [
                        'original' => '/images/' . $subdirectory_name . '/' . $original_file,
                        'thumb' => '/images/' . $subdirectory_name . '/' . $thumb_file,
                        'metadata' => $metadata
                    ];
                }
            }
        }


        $totalImages = count($allImages);

        // Calculate the starting offset for the array slice
        $offset = ($page - 1) * $perPage;

        // Get the specific "slice" of the array for the current page
        $paginatedImages = array_slice($allImages, $offset, $perPage);

        // Return both the images for the page and the total count
        return [
            'images' => $paginatedImages,
            'total' => $totalImages
        ];
    }

    public function getErrors() {
        return $this->errors;
    }
}

