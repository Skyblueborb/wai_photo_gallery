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

    public static function searchImagesByTitle($searchTerm, $username) {
        $safeSearchTerm = preg_quote($searchTerm, '/');

        // i option means case insensitive.
        $titleFilter = [
            'title' => [
                '$regex' => $safeSearchTerm,
                '$options' => 'i'
            ]
        ];
        $visibilityFilter = ['type' => 'public'];
        if ($username) {
            $visibilityFilter = [
                '$or' => [
                    ['type' => 'public'],
                    ['author' => $username, 'type' => 'private']
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

            return $imagesCollection->find($finalFilter)->toArray();

        } catch (Exception) {
            return [];
        }
    }

    public static function getVisiblePhotosPaginated($username, $page, $perPage) {
        $filter = ['type' => 'public'];

        if ($username !== null) {
            $filter = [
                '$or' => [
                    ['type' => 'public'],
                    ['author' => $username, 'type' => 'private']
                ]
            ];
        }

        try {
            $imagesCollection = self::getCollection('images');
            if (!$imagesCollection) return ['documents' => [], 'total' => 0];

            $total = $imagesCollection->countDocuments($filter);

            $options = [
                'sort' => ['_id' => -1],
                'skip' => ($page - 1) * $perPage,
                'limit' => $perPage
            ];

            $cursor = $imagesCollection->find($filter, $options);

            return [
                'documents' => $cursor->toArray(),
                'total' => $total
            ];

        } catch (Exception) {
            return ['documents' => [], 'total' => 0];
        }
    }

    public static function getUser($username) {
        $user = self::findOne('username', $username, 'users');
        return $user;
    }
}

