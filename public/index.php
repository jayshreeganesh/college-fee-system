<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 1. Define Base Path for easy file inclusion
define('BASE_PATH', dirname(__DIR__));

// 2. Load Composer Autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// 3. Very Basic Router
// In a real MVC, we use a Router class, but this demonstrates the concept
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base directory if running in a subfolder like XAMPP
// Since we will run this via 'php -S localhost:8000', $uri is just '/'
if ($uri === '/' || $uri === '/index.php') {
    $controller = new \App\Controllers\HomeController();
    $controller->index();
} elseif ($uri === '/login') {
    $controller = new \App\Controllers\AdminController();
    $controller->login();
} elseif ($uri === '/logout') {
    session_destroy();
    header('Location: /');
    exit;
} elseif ($uri === '/admin') {
    $controller = new \App\Controllers\AdminController();
    $controller->dashboard();
} elseif ($uri === '/admin/export') {
    $controller = new \App\Controllers\AdminController();
    $controller->export();
} elseif ($uri === '/admin/payment') {
    $controller = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->storePayment();
    } else {
        $controller->addPayment();
    }
} elseif ($uri === '/student/login') {
    $controller = new \App\Controllers\StudentController();
    $controller->login();
} elseif ($uri === '/student') {
    $controller = new \App\Controllers\StudentController();
    $controller->portal();
} else {
    http_response_code(404);
    require_once BASE_PATH . '/app/Views/errors/404.php';
}
