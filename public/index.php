<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 1. Define Base Path for easy file inclusion
define('BASE_PATH', dirname(__DIR__));

// 2. Native PSR-4 Autoloader (No Composer Required for Production)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = BASE_PATH . '/app/';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
// 3. Very Basic Router
// In a real MVC, we use a Router class, but this demonstrates the concept
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base directory if running in a subfolder like XAMPP
// Since we will run this via 'php -S localhost:8000', $uri is just '/'

// --- Setup Lock Facility ---
$lockFile = dirname(__DIR__) . '/setup.lock';

if (!file_exists($lockFile)) {
    // If lock doesn't exist, ONLY allow the setup route
    if ($uri !== '/setup') {
        header('Location: /setup');
        exit;
    }
} else {
    // If lock DOES exist, completely block the setup route
    if ($uri === '/setup') {
        die('System is already securely installed. Remove setup.lock to reinstall.');
    }
}

if ($uri === '/setup') {
    $controller = new \App\Controllers\SetupController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->install();
    } else {
        $controller->index();
    }
} elseif ($uri === '/' || $uri === '/index.php') {
    $controller = new \App\Controllers\HomeController();
    $controller->index();
} elseif ($uri === '/login') {
    $controller = new \App\Controllers\AdminController();
    $controller->login();
} elseif ($uri === '/forgot-password') {
    $controller = new \App\Controllers\AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->sendResetLink();
    } else {
        $controller->forgotPassword();
    }
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
} elseif ($uri === '/admin/reports') {
    $controller = new \App\Controllers\AdminController();
    $controller->reports();
} elseif ($uri === '/admin/backup') {
    $controller = new \App\Controllers\AdminController();
    $controller->backupDatabase();
} elseif ($uri === '/admin/restore') {
    $controller = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->restoreDatabase();
    }
} elseif ($uri === '/admin/export-project') {
    $controller = new \App\Controllers\AdminController();
    $controller->exportProject();
} elseif ($uri === '/admin/settings') {
    $controller = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->updateSettings();
    } else {
        $controller->settings();
    }
} elseif ($uri === '/admin/fees') {
    $controller = new \App\Controllers\AdminController();
    $controller->listFees();
} elseif ($uri === '/admin/fees/add') {
    $controller = new \App\Controllers\AdminController();
    $controller->storeFee();
} elseif ($uri === '/admin/fees/delete') {
    $controller = new \App\Controllers\AdminController();
    $controller->deleteFee();
} elseif ($uri === '/admin/audit-logs') {
    $controller = new \App\Controllers\AdminController();
    $controller->auditLogs();
} elseif ($uri === '/admin/emails') {
    $controller = new \App\Controllers\AdminController();
    $controller->emailLogs();
} elseif ($uri === '/admin/student/import/template') {
    $controller = new \App\Controllers\AdminController();
    $controller->downloadImportTemplate();
} elseif ($uri === '/admin/send-reminders') {
    $controller = new \App\Controllers\AdminController();
    $controller->sendReminders();
} elseif ($uri === '/admin/seed-demo-data') {
    $controller = new \App\Controllers\AdminController();
    $controller->seedDemoData();
} elseif ($uri === '/admin/users') {
    $controller = new \App\Controllers\AdminController();
    $controller->listUsers();
} elseif ($uri === '/admin/users/add') {
    $controller = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->storeUser();
    } else {
        $controller->addUser();
    }
} elseif ($uri === '/admin/users/delete') {
    $controller = new \App\Controllers\AdminController();
    $controller->deleteUser();
} elseif ($uri === '/receipt') {
    $controller = new \App\Controllers\ReceiptController();
    $controller->show();
} elseif ($uri === '/admin/payment') {
    $controller = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->storePayment();
    } else {
        $controller->addPayment();
    }
} elseif ($uri === '/admin/student/add') {
    $controller = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->storeStudent();
    } else {
        $controller->addStudent();
    }
} elseif ($uri === '/admin/student/import') {
    $controller = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->processImportStudents();
    } else {
        $controller->importStudents();
    }
} elseif ($uri === '/student/login') {
    $controller = new \App\Controllers\StudentController();
    $controller->login();
} elseif ($uri === '/student') {
    $controller = new \App\Controllers\StudentController();
    $controller->portal();
} elseif ($uri === '/student/pay') {
    $controller = new \App\Controllers\StudentController();
    $controller->checkout();
} elseif ($uri === '/student/pay/process') {
    $controller = new \App\Controllers\StudentController();
    $controller->processPayment();
} elseif ($uri === '/student/profile/update') {
    $controller = new \App\Controllers\StudentController();
    $controller->updateProfile();
} else {
    http_response_code(404);
    require_once BASE_PATH . '/app/Views/errors/404.php';
}
