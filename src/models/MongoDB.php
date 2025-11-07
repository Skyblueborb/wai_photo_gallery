<?php

require_once BASE_PATH . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor/autoload.php';

define('MONGO_DB_URI', 'mongodb://localhost:27017/wai');

class MongoDB {
    private static $instance = null;

    private $client;

    private function __construct() {
        try {
            $this->client = new MongoDB\Client(MONGO_DB_URI,
            [
                "username" => "wai_web",
                "password" => "w@i_w3b",
            ]);
        } catch (Exception $e) {
            die("Error connecting to MongoDB: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MongoDB();
        }
        return self::$instance;
    }

    public function getClient() {
        return $this->client;
    }

    public function getDatabase() {
        return $this->client->selectDatabase("wai");
    }
}
