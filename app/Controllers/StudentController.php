<?php

namespace App\Controllers;

use App\Database\DatabaseConnection;
use PDO;

class StudentController
{
    public function login()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed.');
            }

            $enrollment_number = $_POST['enrollment_number'] ?? '';
            $password = $_POST['password'] ?? '';

            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();

            $stmt = $pdo->prepare('SELECT * FROM students WHERE enrollment_number = :enrollment_number');
            $stmt->execute(['enrollment_number' => $enrollment_number]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student && password_verify($password, $student['password'])) {
                $_SESSION['student_id'] = $student['id'];
                header('Location: /student');
                exit;
            } else {
                $error = 'Invalid enrollment number or password.';
            }
        }

        $pageTitle = "Student Login - College Fee System";
        require_once BASE_PATH . '/app/Views/student/login.php';
    }

    public function portal()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: /student/login');
            exit;
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $studentId = $_SESSION['student_id'];

        // Get student details
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
        $stmt->execute([$studentId]);
        $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Save enrollment to session for receipt verification if not already there
        $_SESSION['student_enrollment'] = $studentData['enrollment_number'];

        // Get totals
        $stmtTotal = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE student_id = ? AND status = 'paid'");
        $stmtTotal->execute([$studentId]);
        $totalPaid = $stmtTotal->fetchColumn() ?: 0;

        $stmtPending = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE student_id = ? AND status = 'pending'");
        $stmtPending->execute([$studentId]);
        $totalPending = $stmtPending->fetchColumn() ?: 0;

        // Get recent transactions
        $stmtTx = $pdo->prepare('
            SELECT t.id, t.amount, t.status, t.created_at, f.name as fee_name 
            FROM transactions t 
            JOIN fee_categories f ON t.fee_category_id = f.id 
            WHERE t.student_id = ? 
            ORDER BY t.created_at DESC
        ');
        $stmtTx->execute([$studentId]);
        $transactions = $stmtTx->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Student Portal - College Fee System";
        require_once BASE_PATH . '/app/Views/student/portal.php';
    }

    public function checkout()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: /student/login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /student');
            exit;
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        // Fetch the pending transaction ensuring it belongs to this student
        $stmt = $pdo->prepare('
            SELECT t.*, f.name as fee_name 
            FROM transactions t 
            JOIN fee_categories f ON t.fee_category_id = f.id 
            WHERE t.id = ? AND t.student_id = ? AND t.status = "pending"
        ');
        $stmt->execute([$id, $_SESSION['student_id']]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            die('Invalid or already paid transaction.');
        }

        $pageTitle = "Secure Checkout - College Fee System";
        require_once BASE_PATH . '/app/Views/student/checkout.php';
    }

    public function processPayment()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: /student/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed.');
            }

            $id = $_POST['transaction_id'] ?? null;
            if ($id) {
                $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
                $pdo = $db->getPdo();

                // Update to paid
                $stmt = $pdo->prepare('UPDATE transactions SET status = "paid" WHERE id = ? AND student_id = ? AND status = "pending"');
                $stmt->execute([$id, $_SESSION['student_id']]);
            }
        }
        
        header('Location: /student?payment=success');
        exit;
    }
}
