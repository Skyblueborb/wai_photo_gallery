<?php

define("BASE_PATH", __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);

require_once BASE_PATH . 'routing.php';

//aspekty globalne
session_start();

// Get the requested URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Initialize the router and define routes
$router = new Router();
$router->addRoute('GET', '/', 'GalleryController', 'index');
$router->addRoute('GET', '/upload', 'ImageUploadController', 'showForm');
/* $router->addRoute('GET', '/', 'TestController', 'showPage'); */
$router->addRoute('POST', '/upload', 'ImageUploadController', 'handleUpload');

// Dispatch the request
$router->dispatch($requestMethod, $requestUri);
