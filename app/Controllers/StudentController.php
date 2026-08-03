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
        // Protect the portal
        if (!isset($_SESSION['student_id'])) {
            header('Location: /student/login');
            exit;
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        // Fetch actual student data
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['student_id']]);
        $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch transactions
        $stmt = $pdo->prepare('SELECT * FROM transactions WHERE student_id = :student_id ORDER BY created_at DESC');
        $stmt->execute(['student_id' => $_SESSION['student_id']]);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalPaid = 0;
        $pendingDues = 0;
        foreach ($transactions as $tx) {
            if ($tx['status'] === 'paid') {
                $totalPaid += $tx['amount'];
            } else {
                $pendingDues += $tx['amount'];
            }
        }
        $lastTransaction = count($transactions) > 0 ? '$' . number_format($transactions[0]['amount'], 2) . ' (' . $transactions[0]['status'] . ')' : 'None';

        $pageTitle = "Student Portal - College Fee System";
        require_once BASE_PATH . '/app/Views/student/portal.php';
    }
}
