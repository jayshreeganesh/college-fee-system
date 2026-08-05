<?php

// C:\php-mysql-prj\college-fee-system\database\migrate_advanced.php
$db = new PDO('sqlite:' . __DIR__ . '/app.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("
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
    INSERT OR IGNORE INTO settings (key, value) VALUES ('college_name', 'TECH UNIVERSITY');
    INSERT OR IGNORE INTO settings (key, value) VALUES ('college_address', '123 Innovation Drive, Tech City, TX 75001');
    INSERT OR IGNORE INTO settings (key, value) VALUES ('college_contact', 'contact@techuniversity.edu | (555) 123-4567');
");
echo "Advanced tables migrated successfully.\n";
