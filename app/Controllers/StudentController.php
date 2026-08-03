<?php

namespace App\Controllers;

class StudentController
{
    public function portal()
    {
        $pageTitle = "Student Portal - College Fee System";
        require_once BASE_PATH . '/app/Views/student/portal.php';
    }
}
