<?php

namespace App\Controllers;

use App\Core\DatabaseConnection;

class SetupController
{
    public function index()
    {
        $pageTitle = "System Setup - College Fee System";
        require_once BASE_PATH . '/app/Views/setup.php';
    }

    public function install()
    {
        if (file_exists(BASE_PATH . '/setup.lock')) {
            die('System is already installed.');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            die('Username and Password are required.');
        }

        try {
            $dbPath = BASE_PATH . '/database/app.sqlite';
            $db = new DatabaseConnection('sqlite:' . $dbPath);
            $pdo = $db->getPdo();

            // 1. Create Tables
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS admins (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    username TEXT NOT NULL UNIQUE,
                    password TEXT NOT NULL,
                    role TEXT NOT NULL DEFAULT 'super_admin'
                );
                
                CREATE TABLE IF NOT EXISTS fee_categories (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL UNIQUE,
                    amount REAL NOT NULL
                );
                
                CREATE TABLE IF NOT EXISTS students (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    enrollment_number TEXT NOT NULL UNIQUE,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    course TEXT NOT NULL,
                    password TEXT NOT NULL
                );
                
                CREATE TABLE IF NOT EXISTS transactions (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    student_id INTEGER NOT NULL,
                    fee_category_id INTEGER NOT NULL,
                    amount REAL NOT NULL,
                    status TEXT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (student_id) REFERENCES students(id),
                    FOREIGN KEY (fee_category_id) REFERENCES fee_categories(id)
                );
            ");

            // 2. Insert Super Admin
            $stmt = $pdo->prepare('INSERT INTO admins (username, password, role) VALUES (?, ?, ?)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), 'super_admin']);

            // 3. Insert Default Fee Categories so app is usable immediately
            $pdo->exec("
                INSERT OR IGNORE INTO fee_categories (name, amount) VALUES 
                ('Tuition Fee', 1500.00),
                ('Library Fee', 100.00),
                ('Hostel Fee', 800.00)
            ");

            // 4. Create Lock File
            file_put_contents(BASE_PATH . '/setup.lock', 'LOCKED');

            header('Location: /login?setup=success');
            exit;

        } catch (\PDOException $e) {
            die('Installation Failed: ' . $e->getMessage());
        }
    }
}
