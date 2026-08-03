<?php

namespace Tests\Models;

use App\Database\DatabaseConnection;
use App\Models\Student;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    private \PDO $pdo;

    protected function setUp(): void
    {
        // Setup an in-memory SQLite database for testing
        $db = new DatabaseConnection('sqlite::memory:');
        $this->pdo = $db->getPdo();

        // Create the students table required for the test
        $this->pdo->exec('CREATE TABLE students (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            enrollment_number TEXT NOT NULL,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            course TEXT NOT NULL
        )');
    }

    public function testCanCreateAndSaveStudent(): void
    {
        // Arrange: Create a new Student instance
        $student = new Student();
        $student->enrollment_number = 'CS2026-001';
        $student->name = 'Alice Smith';
        $student->email = 'alice@example.com';
        $student->course = 'Computer Science';

        // Act: Save the student to the database
        $result = $student->save($this->pdo);

        // Assert: Verify it saved correctly and auto-generated an ID
        $this->assertTrue($result);
        $this->assertNotNull($student->id);

        // Verify data actually exists in the SQLite database
        $stmt = $this->pdo->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute(['id' => $student->id]);
        $savedData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals('Alice Smith', $savedData['name']);
        $this->assertEquals('CS2026-001', $savedData['enrollment_number']);
    }
}
