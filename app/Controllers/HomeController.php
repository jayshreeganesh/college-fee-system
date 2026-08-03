<?php

namespace app\Controllers;

class HomeController
{
    public function index()
    {
        // In a full MVC, the controller would fetch data from a Model here.
        // For the home page, we just want to load the beautiful Tailwind View!

        $pageTitle = "College Fee Management System";

        // Include the view file
        require_once BASE_PATH . '/app/Views/home.php';
    }
}
