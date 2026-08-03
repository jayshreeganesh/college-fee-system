<?php

namespace App\Controllers;

class AdminController
{
    public function login()
    {
        $pageTitle = "Admin Login - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/login.php';
    }
}
