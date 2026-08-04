<?php

/**
 * Development Server Router
 * This file allows running the application simply with: php -S localhost:8000
 * It automatically forwards all requests to the public/index.php front-controller.
 */

// Pass through static files (images, css, js) if they exist
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

// Otherwise, forward to the front controller
require_once __DIR__ . '/public/index.php';
