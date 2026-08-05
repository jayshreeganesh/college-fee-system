<?php

namespace App\Controllers;

class AuthController
{
    public function forgotPassword()
    {
        $pageTitle = "Forgot Password - College Fee System";
        $success = $_GET['success'] ?? false;
        require_once BASE_PATH . '/app/Views/auth/forgot.php';
    }

    public function sendResetLink()
    {
        // Mock email sending for MVP
        $identifier = $_POST['identifier'] ?? '';

        if (!empty($identifier)) {
            // In a production app, we would query the database here, generate a secure token,
            // and send an email via SMTP.

            // For MVP Demo purposes, we simply redirect to the forgot page with a success flag
            // to demonstrate the UI flow to the user.
            header('Location: /forgot-password?success=1');
            exit;
        }

        header('Location: /forgot-password');
        exit;
    }
}
