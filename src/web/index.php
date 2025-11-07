<?php

define("BASE_PATH", __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);

require_once BASE_PATH . 'routing.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'DatabaseUtils.php';

//aspekty globalne
session_start();

// Get the requested URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Initialize DatabaseUtils
DatabaseUtils::init();

$router = new Router();
$router->addRoute('GET', '/', 'GalleryController', 'showGallery');
$router->addRoute('GET', '/upload', 'ImageUploadController', 'showForm');
$router->addRoute('POST', '/upload', 'ImageUploadController', 'handleUpload');

# Login/Registration
$router->addRoute('GET', '/login', 'UserController', 'showLogin');
$router->addRoute('GET', '/logout', 'UserController', 'logout');
$router->addRoute('GET', '/register', 'UserController', 'showRegister');
$router->addRoute('POST', '/login', 'UserController', 'handleLogin');
$router->addRoute('POST', '/register', 'UserController','handleRegister');

# Saved images
$router->addRoute('GET', '/saved', 'GalleryController', 'showSaved');
$router->addRoute('POST', '/save', 'GalleryController', 'handleSavedForm');

# AJAX Search
$router->addRoute('GET', '/search', 'SearchController', 'showSearchPage');
$router->addRoute('GET', '/search/ajax', 'SearchController', 'ajaxSearch');


// Dispatch the request
$router->dispatch($requestMethod, $requestUri);
