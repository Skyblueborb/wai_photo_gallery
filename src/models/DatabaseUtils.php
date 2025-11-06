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
            $filter = [$key => $query];

            $document = $target_collection->findOne($filter);

            return $document;
        } catch (Exception) {
            return null;
        }
    }

    public static function getUser($username) {
        $user = self::findOne('username', $username, 'users');
        return $user;
    }
}

