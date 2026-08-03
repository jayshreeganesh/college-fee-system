<?php

// C:\php-mysql-prj\college-fee-system\public\index.php

// 1. Define Base Path for easy file inclusion
define('BASE_PATH', dirname(__DIR__));

// 2. Simple Auto-loader for Controllers and Models
spl_autoload_register(function ($class_name) {
    // Convert namespace backslashes to directory separators
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class_name) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 3. Very Basic Router
// In a real MVC, we use a Router class, but this demonstrates the concept
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base directory if running in a subfolder like XAMPP
// Since we will run this via 'php -S localhost:8000', $uri is just '/'
if ($uri === '/' || $uri === '/index.php') {
    // Route to HomeController
    $controller = new \app\Controllers\HomeController();
    $controller->index();
} else {
    // 404 Not Found
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}
