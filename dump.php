<?php
$db = new PDO('sqlite:database/app.sqlite');
$admin = $db->query("SELECT * FROM admins WHERE username = 'admin'")->fetch(PDO::FETCH_ASSOC);
echo "Admin pass verify: " . (password_verify('admin123', $admin['password']) ? 'true' : 'false') . "\n";
$student = $db->query("SELECT * FROM students WHERE enrollment_number = 'CS2026-001'")->fetch(PDO::FETCH_ASSOC);
echo "Student pass verify: " . (password_verify('password123', $student['password']) ? 'true' : 'false') . "\n";
