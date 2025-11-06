<?php

// Make sure you have run "composer require mongodb/mongodb"
require_once BASE_PATH . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor/autoload.php';

// Define your MongoDB connection details as constants for easy configuration
define('MONGO_DB_URI', 'mongodb://localhost:27017/wai');

class MongoDB {
    // This will hold the single instance of our connection
    private static $instance = null;

    // This will hold the actual connected MongoDB client object
    private $client;

    /**
     * The constructor is private to prevent creating new instances from outside.
     * This is the core of the Singleton pattern.
     */
    private function __construct() {
        try {
            // Create the new MongoDB client connection
            $this->client = new MongoDB\Client(MONGO_DB_URI,
            [
                "username" => "wai_web",
                "password" => "w@i_w3b",
            ]);
        } catch (Exception $e) {
            // If the connection fails, it's a critical error.
            // In a real app, you'd log this error instead of echoing.
            die("Error connecting to MongoDB: " . $e->getMessage());
        }
    }

    /**
     * The static method that controls access to the single instance.
     * This is how other parts of your code will get the connection.
     *
     * @return MongoConnection The single instance of the connection.
     */
    public static function getInstance() {
        if (self::$instance == null) {
            // If no instance exists yet, create one.
            self::$instance = new MongoDB();
        }
        return self::$instance;
    }

    /**
     * Returns the actual MongoDB client object to work with.
     *
     * @return \MongoDB\Client The connected client.
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * A helper method to quickly select our main database.
     *
     * @return \MongoDB\Database The selected database.
     */
    public function getDatabase() {
        return $this->client->selectDatabase("wai");
    }
}
