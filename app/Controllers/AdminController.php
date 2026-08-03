<?php

namespace App\Controllers;

use App\Database\DatabaseConnection;
use PDO;
use ZipArchive;

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
                header('Location: /admin');
                exit;
            } else {
                $error = 'Invalid admin credentials.';
            }
        }

        $pageTitle = "Admin Login - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/login.php';
    }

    public function dashboard()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $studentCount = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
        $totalCollected = $pdo->query("SELECT SUM(amount) FROM transactions WHERE status = 'paid'")->fetchColumn() ?: 0;
        $totalPending = $pdo->query("SELECT SUM(amount) FROM transactions WHERE status = 'pending'")->fetchColumn() ?: 0;

        $recentTransactions = $pdo->query('
            SELECT t.amount, t.status, t.created_at, s.name as student_name, f.name as fee_name 
            FROM transactions t 
            JOIN students s ON t.student_id = s.id 
            JOIN fee_categories f ON t.fee_category_id = f.id 
            ORDER BY t.created_at DESC 
            LIMIT 5
        ')->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Admin Dashboard";
        require_once BASE_PATH . '/app/Views/admin/dashboard.php';
    }

    public function export()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        // 1. Generate CSV data
        $transactions = $pdo->query('
            SELECT t.id, s.name as student, f.name as category, t.amount, t.status, t.created_at 
            FROM transactions t 
            JOIN students s ON t.student_id = s.id 
            JOIN fee_categories f ON t.fee_category_id = f.id
        ')->fetchAll(PDO::FETCH_ASSOC);

        $csvFile = sys_get_temp_dir() . '/export_' . time() . '.csv';
        $fp = fopen($csvFile, 'w');
        fputcsv($fp, ['ID', 'Student', 'Fee Category', 'Amount', 'Status', 'Date']);
        foreach ($transactions as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);

        // 2. Create ZIP archive
        $zipFile = sys_get_temp_dir() . '/college_fees_export_' . time() . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
            $zip->addFile($csvFile, 'transactions.csv');
            $zip->close();
        }

        // 3. Send to browser
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=fee_export.zip');
        header('Content-Length: ' . filesize($zipFile));
        readfile($zipFile);

        // Clean up
        unlink($csvFile);
        unlink($zipFile);
        exit;
    }
}
