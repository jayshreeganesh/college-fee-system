<?php

namespace App\Controllers;

use App\Database\DatabaseConnection;
use PDO;

class AdminController
{
    public function login()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();

            $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                // Redirect to admin dashboard (not built yet, redirect home for now)
                header('Location: /');
                exit;
            } else {
                $error = 'Invalid admin credentials.';
            }
        }

        $pageTitle = "Admin Login - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/login.php';
    }
}
