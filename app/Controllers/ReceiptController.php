<?php

namespace App\Controllers;

use App\Core\DatabaseConnection;
use PDO;

class ReceiptController
{
    public function show()
    {
        // Must be logged in as either Admin or Student
        if (!isset($_SESSION['admin_id']) && !isset($_SESSION['student_id'])) {
            header('Location: /');
            exit;
        }

        $transactionId = $_GET['id'] ?? null;
        if (!$transactionId) {
            die('Invalid Transaction ID.');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $stmt = $pdo->prepare('
            SELECT t.id, t.amount, t.status, t.created_at, 
                   s.name as student_name, s.enrollment_number, s.course, 
                   f.name as fee_name 
            FROM transactions t 
            JOIN students s ON t.student_id = s.id 
            JOIN fee_categories f ON t.fee_category_id = f.id 
            WHERE t.id = ?
        ');
        $stmt->execute([$transactionId]);
        $receipt = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$receipt) {
            die('Transaction not found.');
        }

        // If logged in as student, verify they own this receipt
        if (isset($_SESSION['student_id']) && !isset($_SESSION['admin_id'])) {
            $studentCheck = $pdo->prepare('SELECT id FROM students WHERE enrollment_number = ?');
            $studentCheck->execute([$_SESSION['student_enrollment'] ?? '']);
            $student = $studentCheck->fetch(PDO::FETCH_ASSOC);

            // Re-fetch using actual ID to be perfectly secure
            $stmtSec = $pdo->prepare('SELECT student_id FROM transactions WHERE id = ?');
            $stmtSec->execute([$transactionId]);
            $owner = $stmtSec->fetchColumn();

            if ($owner != ($student['id'] ?? -1)) {
                die('Access Denied: You do not have permission to view this receipt.');
            }
        }

        $settingsQuery = $pdo->query('SELECT key, value FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
        $settings = array_merge([
            'college_name' => 'TECH UNIVERSITY',
            'college_address' => '123 Innovation Drive, Tech City, TX 75001',
            'college_contact' => 'contact@techuniversity.edu | (555) 123-4567',
        ], $settingsQuery ?: []);

        $pageTitle = "Receipt #" . str_pad($receipt['id'], 6, '0', STR_PAD_LEFT);
        require_once BASE_PATH . '/app/Views/receipt.php';
    }
}
