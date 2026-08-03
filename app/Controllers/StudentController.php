<?php

namespace App\Controllers;

class StudentController
{
    public function login()
    {
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

        $pageTitle = "Student Portal - College Fee System";
        require_once BASE_PATH . '/app/Views/student/portal.php';
    }
}
