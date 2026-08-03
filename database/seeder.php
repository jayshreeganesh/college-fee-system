<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Database\DatabaseConnection;
use App\Models\FeeCategory;
use App\Models\Student;
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

$pdo->exec("
    CREATE TABLE students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        enrollment_number TEXT NOT NULL,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        course TEXT NOT NULL
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
    CREATE TABLE transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER NOT NULL,
        fee_category_id INTEGER NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        status TEXT NOT NULL DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(student_id) REFERENCES students(id),
        FOREIGN KEY(fee_category_id) REFERENCES fee_categories(id)
    )
");

echo "Tables created successfully.\n";

// 2. Seed Fee Categories
$tuition = new FeeCategory();
$tuition->name = 'Tuition Fee';
$tuition->description = 'Standard tuition fee for Fall 2026';
$tuition->save($pdo);

$library = new FeeCategory();
$library->name = 'Library Fee';
$library->description = 'Annual library and resources fee';
$library->save($pdo);

echo "Fee Categories seeded.\n";

// 3. Seed Students
$student1 = new Student();
$student1->enrollment_number = 'CS2026-001';
$student1->name = 'Alice Smith';
$student1->email = 'alice@example.com';
$student1->course = 'Computer Science';
$student1->save($pdo);

$student2 = new Student();
$student2->enrollment_number = 'BA2026-042';
$student2->name = 'Bob Johnson';
$student2->email = 'bob@example.com';
$student2->course = 'Business Administration';
$student2->save($pdo);

echo "Students seeded.\n";

// 4. Seed Transactions
$tx1 = new Transaction();
$tx1->student_id = $student1->id;
$tx1->fee_category_id = $tuition->id;
$tx1->amount = 1500.00;
$tx1->status = 'paid';
$tx1->save($pdo);

$tx2 = new Transaction();
$tx2->student_id = $student1->id;
$tx2->fee_category_id = $library->id;
$tx2->amount = 200.00;
$tx2->status = 'pending';
$tx2->save($pdo);

$tx3 = new Transaction();
$tx3->student_id = $student2->id;
$tx3->fee_category_id = $tuition->id;
$tx3->amount = 1200.00;
$tx3->status = 'pending';
$tx3->save($pdo);

echo "Transactions seeded.\n";
echo "Database seeding completed successfully!\n";
