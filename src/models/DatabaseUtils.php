<?php

require_once BASE_PATH . 'models/MongoDB.php';

class DatabaseUtils {
    private static $db = null;
    private static $image_collection;
    private static $user_collection;


    public static function init() {
        if (self::$db === null) {
            $mongoInstance = MongoDB::getInstance();
            self::$db = $mongoInstance->getDatabase();

            self::$image_collection = self::$db->images;
            self::$user_collection = self::$db->users;
        }
    }

    private static function getCollection($name) {
        switch ($name) {
            case 'images':
                return self::$image_collection;
            case 'users':
                return self::$user_collection;
            default:
                // Return null if an invalid collection name is requested
                return null;
        }
    }


    public static function saveDocument($document, $collection) {
        $target_collection = self::getcollection($collection);
        if ($target_collection === null) {
            return false;
        }

        try {
            $insertOneResult = $target_collection->insertOne($document);

            return $insertOneResult->getInsertedCount() === 1;
        } catch (Exception) {
            return false;
        }
        return true;
    }

    public static function findOne($key, $query, $collection) {
        $target_collection = self::getcollection($collection);
        if ($target_collection === null) {
            return false;
        }

        $filter = [$key => $query];
        try {
            $document = $target_collection->findOne($filter);

            return $document;
        } catch (Exception) {
            return null;
        }
    }

    public static function getLoggedInPhotos($username) {
        $target_collection = self::$image_collection;
        if ($target_collection === null) {
            return false;
        }

        $query = [
            '$or' => [
                ['type' => 'public'],
                ['author' => $username, 'type' => 'private']
            ]
        ];

        try {
            $cursor = $target_collection->find($query);

            return $cursor->toArray();
        } catch (Exception $e) {
            echo $e;
            return null;
        }

    }

    public static function searchImagesByTitle($searchTerm, $username) {
        // i option means case insensitive.
        $titleFilter = [
            'title' => [
                '$regex' => $searchTerm,
                '$options' => 'i'
            ]
        ];
        $visibilityFilter = ['type' => 'public'];
        if ($username) {
            $visibilityFilter = [
                '$or' => [
                    ['type' => 'public'],
                    ['author' => $username, 'type' => 'private'] // You can see public OR your own
                ]
            ];
        }

        $finalFilter = [
            '$and' => [
                $titleFilter,
                $visibilityFilter
            ]
        ];


        try {
            $imagesCollection = self::getCollection('images');
            if (!$imagesCollection) return [];

            // Execute the query and return the results as an array.
            return $imagesCollection->find($finalFilter)->toArray(); // Limit to 20 results for performance

        } catch (Exception) {
            return [];
        }
    }

    public static function getUser($username) {
        $user = self::findOne('username', $username, 'users');
        return $user;
    }
}

