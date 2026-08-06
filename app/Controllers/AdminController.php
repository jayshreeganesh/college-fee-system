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
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed.');
            }

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();

            $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_role'] = $admin['role'];
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
            SELECT t.id, t.amount, t.status, t.created_at, s.name as student_name, f.name as fee_name 
            FROM transactions t 
            JOIN students s ON t.student_id = s.id 
            JOIN fee_categories f ON t.fee_category_id = f.id 
            ORDER BY t.created_at DESC 
            LIMIT 5
        ')->fetchAll(PDO::FETCH_ASSOC);

        // Fetch Data for ApexCharts (Revenue by Category)
        $chartDataQuery = $pdo->query('
            SELECT f.name as category, SUM(t.amount) as total
            FROM transactions t
            JOIN fee_categories f ON t.fee_category_id = f.id
            WHERE t.status = "paid"
            GROUP BY f.id
        ')->fetchAll(PDO::FETCH_ASSOC);

        $chartLabels = json_encode(array_column($chartDataQuery, 'category'));
        $chartSeries = json_encode(array_map('floatval', array_column($chartDataQuery, 'total')));

        $pageTitle = "Admin Dashboard";
        require_once BASE_PATH . '/app/Views/admin/dashboard.php';
    }

    public function reports()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $statusFilter = $_GET['status'] ?? '';
        $courseFilter = $_GET['course'] ?? '';

        $query = '
            SELECT t.id, t.amount, t.status, t.created_at, s.name as student_name, s.course, f.name as fee_name 
            FROM transactions t 
            JOIN students s ON t.student_id = s.id 
            JOIN fee_categories f ON t.fee_category_id = f.id 
            WHERE 1=1
        ';
        $params = [];

        if ($statusFilter) {
            $query .= ' AND t.status = ?';
            $params[] = $statusFilter;
        }

        if ($courseFilter) {
            $query .= ' AND s.course LIKE ?';
            $params[] = '%' . $courseFilter . '%';
        }

        $query .= ' ORDER BY t.created_at DESC';

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Advanced Reports";
        require_once BASE_PATH . '/app/Views/admin/reports.php';
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

    public function addPayment()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $students = $pdo->query('SELECT id, name, enrollment_number FROM students')->fetchAll(PDO::FETCH_ASSOC);
        $categories = $pdo->query('SELECT id, name FROM fee_categories')->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Add Payment - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/add_payment.php';
    }

    public function storePayment()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        $student_id = $_POST['student_id'] ?? null;
        $fee_category_id = $_POST['fee_category_id'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $status = $_POST['status'] ?? 'paid';

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed.');
        }

        if ($student_id && $fee_category_id && $amount !== null && (float) $amount > 0) {
            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();

            // Validate student_id exists
            $stmt = $pdo->prepare('SELECT id FROM students WHERE id = ?');
            $stmt->execute([(int) $student_id]);
            if (!$stmt->fetch()) {
                die('Invalid student ID.');
            }

            $tx = new \App\Models\Transaction();
            $tx->student_id = (int) $student_id;
            $tx->fee_category_id = (int) $fee_category_id;
            $tx->amount = (float) $amount;
            $tx->status = $status;

            $tx->save($pdo);

            if ($status === 'paid') {
                $studentStmt = $pdo->prepare('SELECT email FROM students WHERE id = ?');
                $studentStmt->execute([(int) $student_id]);
                $email = $studentStmt->fetchColumn();
                if ($email) {
                    $logStmt = $pdo->prepare('INSERT INTO email_logs (recipient_email, subject, body) VALUES (?, ?, ?)');
                    $logStmt->execute([
                        $email,
                        "Payment Receipt (Generated by Admin)",
                        "<p>Your payment of \${$amount} has been successfully recorded.</p>"
                    ]);
                }
            }

            $this->logAction($pdo, "Added payment of \${$amount} for student ID {$student_id}");
        }

        header('Location: /admin');
        exit;
    }

    public function addStudent()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        $pageTitle = "Add Student - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/add_student.php';
    }

    public function storeStudent()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed.');
        }

        $enrollment = trim($_POST['enrollment_number'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($enrollment && $name && $email && $course && $password) {
            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Using raw insert because we added password to schema but our active record Model doesn't explicitly map all fields if not defined
            // Alternatively, just do raw insert for safety.
            $stmt = $pdo->prepare('INSERT INTO students (enrollment_number, name, email, course, password) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$enrollment, $name, $email, $course, $hashedPassword]);
        }

        header('Location: /admin');
        exit;
    }

    public function backupDatabase()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        $dbPath = dirname(__DIR__, 2) . '/database/app.sqlite';
        if (file_exists($dbPath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="database_backup_' . date('Y-m-d_His') . '.sqlite"');
            header('Content-Length: ' . filesize($dbPath));
            readfile($dbPath);
            exit;
        }
        header('Location: /admin');
        exit;
    }

    public function importStudents()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        $pageTitle = "Import Students - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/import_students.php';
    }

    public function processImportStudents()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed.');
        }

        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();

            $file = fopen($_FILES['csv_file']['tmp_name'], 'r');

            // Skip the header row
            fgetcsv($file);

            $stmt = $pdo->prepare('INSERT INTO students (enrollment_number, name, email, course, password) VALUES (?, ?, ?, ?, ?)');

            $pdo->beginTransaction();
            while (($data = fgetcsv($file)) !== false) {
                // Expected CSV Format: Enrollment Number, Name, Email, Course, Default Password
                if (count($data) >= 5) {
                    $enrollment = trim($data[0]);
                    $name = trim($data[1]);
                    $email = trim($data[2]);
                    $course = trim($data[3]);
                    $password = password_hash(trim($data[4]), PASSWORD_DEFAULT);

                    $stmt->execute([$enrollment, $name, $email, $course, $password]);
                }
            }
            $pdo->commit();
            fclose($file);
        }

        header('Location: /admin');
        exit;
    }

    public function restoreDatabase()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed.');
        }

        if (isset($_FILES['database_file']) && $_FILES['database_file']['error'] === UPLOAD_ERR_OK) {
            $dbPath = dirname(__DIR__, 2) . '/database/app.sqlite';
            move_uploaded_file($_FILES['database_file']['tmp_name'], $dbPath);
        }

        header('Location: /admin');
        exit;
    }

    public function exportProject()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        $projectPath = dirname(__DIR__, 2);
        $zipFile = $projectPath . '/college-fee-system.zip';

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $directory = new \RecursiveDirectoryIterator($projectPath, \RecursiveDirectoryIterator::SKIP_DOTS);
            $filter = new \RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator) {
                $exclude = ['.git', 'node_modules', 'vendor', 'college-fee-system.zip', 'project_source_export.zip', 'screenshots'];
                if ($current->isDir() && in_array($current->getFilename(), $exclude, true)) {
                    return false;
                }
                return true;
            });
            $files = new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::LEAVES_ONLY);

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($projectPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();

            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=college-fee-system.zip');
            header('Content-Length: ' . filesize($zipFile));
            readfile($zipFile);
            unlink($zipFile);
            exit;
        } else {
            die('Failed to create zip file.');
        }
    }

    public function listUsers()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to view this page.');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();
        $admins = $pdo->query('SELECT id, username, role FROM admins ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Manage Users - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/users.php';
    }

    public function addUser()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        $pageTitle = "Add User - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/add_user.php';
    }

    public function storeUser()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed.');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'recruiter';

        if (empty($username) || empty($password)) {
            die('Username and Password are required.');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM admins WHERE username = ?');
        $checkStmt->execute([$username]);
        if ($checkStmt->fetchColumn() > 0) {
            die('Error: An account with this username already exists.');
        }

        $stmt = $pdo->prepare('INSERT INTO admins (username, password, role) VALUES (?, ?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);
        $this->logAction($pdo, "Created new admin account for username: {$username}");

        header('Location: /admin/users');
        exit;
    }

    public function deleteUser()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: You do not have permission to perform this action.');
        }

        $id = $_GET['id'] ?? null;
        if ($id && $id != $_SESSION['admin_id']) { // Prevent deleting yourself
            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();
            
            // Get username for audit log
            $stmt = $pdo->prepare('SELECT username FROM admins WHERE id = ?');
            $stmt->execute([$id]);
            $username = $stmt->fetchColumn();

            if ($username) {
                $del = $pdo->prepare('DELETE FROM admins WHERE id = ?');
                $del->execute([$id]);
                $this->logAction($pdo, "Deleted admin account for username: {$username}");
            }
        }
        
        header('Location: /admin/users');
        exit;
    }

    public function emailLogs()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $stmt = $pdo->query("SELECT * FROM email_logs ORDER BY created_at DESC LIMIT 100");
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Email Logs - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/email_logs.php';
    }

    public function settings()
    {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $settingsQuery = $pdo->query('SELECT key, value FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
        $settings = array_merge([
            'college_name' => 'TECH UNIVERSITY',
            'college_address' => '123 Innovation Drive, Tech City, TX 75001',
            'college_contact' => 'contact@techuniversity.edu | (555) 123-4567',
        ], $settingsQuery ?: []);

        $pageTitle = "System Settings - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/settings.php';
    }

    public function downloadImportTemplate()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_import_template.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['name', 'email', 'phone', 'password', 'roll_number']);
        fputcsv($output, ['John Doe', 'john@example.com', '1234567890', 'password123', 'CS101']);
        fputcsv($output, ['Jane Smith', 'jane@example.com', '0987654321', 'password123', 'CS102']);
        fclose($output);
        exit;
    }

    public function seedDemoData()
    {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        // Check if data already seeded to prevent duplicate explosion
        $count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
        if ($count > 5) {
            header('Location: /admin?error=already_seeded');
            exit;
        }

        // Insert Demo Fee Categories
        $pdo->exec("INSERT INTO fee_categories (name, description, amount) VALUES 
            ('Tuition Fee 2026', 'Annual tuition fee', 5000),
            ('Library Fee', 'Library access fee', 200),
            ('Hostel Fee', 'Semester hostel fee', 1200)
        ");

        $feeIds = $pdo->query("SELECT id, amount FROM fee_categories ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);

        // Insert Demo Students & Transactions
        for ($i = 1; $i <= 15; $i++) {
            $name = "Demo Student " . $i;
            $email = "student{$i}@example.com";
            $roll = "DEMO-" . str_pad($i, 3, '0', STR_PAD_LEFT);
            $pass = password_hash('password', PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO students (name, email, phone, password, roll_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, '555-010'.$i, $pass, $roll]);
            $studentId = $pdo->lastInsertId();

            // Assign random fees
            foreach ($feeIds as $fee) {
                if (rand(1, 100) > 30) { // 70% chance to have this fee
                    $status = (rand(1, 100) > 40) ? 'paid' : 'pending';
                    $txStmt = $pdo->prepare("INSERT INTO transactions (student_id, fee_category_id, amount, status) VALUES (?, ?, ?, ?)");
                    $txStmt->execute([$studentId, $fee['id'], $fee['amount'], $status]);
                }
            }
        }

        $this->logAction($pdo, "Seeded demo data successfully.");
        header('Location: /admin?success=seeded');
        exit;
    }

    public function updateSettings()
    {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied');
        }

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed.');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();

        $stmt = $pdo->prepare('INSERT INTO settings (key, value) VALUES (?, ?) ON CONFLICT(key) DO UPDATE SET value = excluded.value');

        $fields = ['college_name', 'college_address', 'college_contact'];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $stmt->execute([$field, trim($_POST[$field])]);
            }
        }

        header('Location: /admin/settings?success=1');
        exit;
    }

    public function listFees()
    {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();
        $fees = $pdo->query('SELECT * FROM fee_categories ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Manage Fee Categories - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/fees.php';
    }

    public function storeFee()
    {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed.');
            }

            $name = trim($_POST['name'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);

            if ($name && $amount > 0) {
                $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
                $pdo = $db->getPdo();
                $stmt = $pdo->prepare('INSERT INTO fee_categories (name, amount) VALUES (?, ?)');
                $stmt->execute([$name, $amount]);
            }
        }
        header('Location: /admin/fees');
        exit;
    }

    public function deleteFee()
    {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied');
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
            $pdo = $db->getPdo();

            // Check if fee is used in transactions
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM transactions WHERE fee_category_id = ?');
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() == 0) {
                $del = $pdo->prepare('DELETE FROM fee_categories WHERE id = ?');
                $del->execute([$id]);
            } else {
                die('Cannot delete fee category because it is already associated with student transactions.');
            }
        }
        header('Location: /admin/fees');
        exit;
    }

    private function logAction($pdo, $action)
    {
        if (isset($_SESSION['admin_id'])) {
            $stmt = $pdo->prepare('SELECT username FROM admins WHERE id = ?');
            $stmt->execute([$_SESSION['admin_id']]);
            $username = $stmt->fetchColumn() ?: 'Unknown';

            $log = $pdo->prepare('INSERT INTO audit_logs (admin_username, action) VALUES (?, ?)');
            $log->execute([$username, $action]);
        }
    }

    public function auditLogs()
    {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'super_admin') {
            die('Access Denied: Only Super Admins can view audit logs.');
        }

        $db = new DatabaseConnection('sqlite:' . dirname(__DIR__, 2) . '/database/app.sqlite');
        $pdo = $db->getPdo();
        $logs = $pdo->query('SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);

        $pageTitle = "Security Audit Logs - College Fee System";
        require_once BASE_PATH . '/app/Views/admin/audit_logs.php';
    }
}
