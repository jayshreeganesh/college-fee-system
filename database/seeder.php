<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Database\DatabaseConnection;
use App\Models\FeeCategory;
use App\Models\Transaction;

echo "Starting Database Seeder...\n";

$dbPath = __DIR__ . '/app.sqlite';
$dsn = 'sqlite:' . $dbPath;

$db = new DatabaseConnection($dsn);
$pdo = $db->getPdo();

echo "Connected to database.\n";

// 1. Create Tables
$pdo->exec("DROP TABLE IF EXISTS transactions");
$pdo->exec("DROP TABLE IF EXISTS fee_categories");
$pdo->exec("DROP TABLE IF EXISTS students");
$pdo->exec("DROP TABLE IF EXISTS admins");

$pdo->exec("
    CREATE TABLE admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'super_admin'
    )
");

$pdo->exec("
    CREATE TABLE students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        enrollment_number TEXT NOT NULL,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        course TEXT NOT NULL,
        password TEXT NOT NULL
    )
");

$pdo->exec("
    CREATE TABLE fee_categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT NOT NULL
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER NOT NULL,
        fee_category_id INTEGER NOT NULL,
        amount REAL NOT NULL,
        status TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id),
        FOREIGN KEY (fee_category_id) REFERENCES fee_categories(id)
    );
    CREATE TABLE IF NOT EXISTS settings (
        key TEXT PRIMARY KEY,
        value TEXT
    );
    CREATE TABLE IF NOT EXISTS audit_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        admin_username TEXT NOT NULL,
        action TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
");

// 2. Insert Settings
$pdo->exec("
    INSERT OR IGNORE INTO settings (key, value) VALUES ('college_name', 'TECH UNIVERSITY');
    INSERT OR IGNORE INTO settings (key, value) VALUES ('college_address', '123 Innovation Drive, Tech City, TX 75001');
    INSERT OR IGNORE INTO settings (key, value) VALUES ('college_contact', 'contact@techuniversity.edu | (555) 123-4567');
");

echo "Tables created successfully.\n";

// Load JSON Demo Data
$jsonData = file_get_contents(__DIR__ . '/demo_data.json');
$data = json_decode($jsonData, true);

if (!$data) {
    die("Failed to parse demo_data.json\n");
}

// 2. Seed Admins
foreach ($data['admins'] as $admin) {
    $hashedPassword = password_hash($admin['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admins (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$admin['username'], $hashedPassword, $admin['role']]);
}
echo "Admins seeded from JSON.\n";

// 3. Seed Fee Categories
$categoryIds = [];
foreach ($data['fee_categories'] as $cat) {
    $feeCat = new FeeCategory();
    $feeCat->name = $cat['name'];
    $feeCat->description = $cat['description'];
    $feeCat->save($pdo);
    $categoryIds[] = $feeCat->id;
}
echo "Fee Categories seeded from JSON.\n";

// 4. Seed Students
$studentIds = [];
foreach ($data['students'] as $stu) {
    $hashedPassword = password_hash($stu['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO students (enrollment_number, name, email, course, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$stu['enrollment_number'], $stu['name'], $stu['email'], $stu['course'], $hashedPassword]);
    $studentIds[] = $pdo->lastInsertId();
}
echo "Students seeded from JSON.\n";

// 5. Seed Transactions
foreach ($data['transactions'] as $txData) {
    $tx = new Transaction();
    $tx->student_id = $studentIds[$txData['student_index']];
    $tx->fee_category_id = $categoryIds[$txData['category_index']];
    $tx->amount = $txData['amount'];
    $tx->status = $txData['status'];
    $tx->save($pdo);
}
echo "Transactions seeded from JSON.\n";

echo "Database seeding completed successfully!\n";
