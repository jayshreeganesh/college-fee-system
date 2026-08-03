<?php

namespace App\Models;

use PDO;

class Student
{
    public ?int $id = null;
    public string $enrollment_number;
    public string $name;
    public string $email;
    public string $course;

    public function save(PDO $pdo): bool
    {
        $sql = "INSERT INTO students (enrollment_number, name, email, course) 
                VALUES (:enrollment_number, :name, :email, :course)";

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            'enrollment_number' => $this->enrollment_number,
            'name'              => $this->name,
            'email'             => $this->email,
            'course'            => $this->course,
        ]);

        if ($result) {
            $this->id = (int) $pdo->lastInsertId();
        }

        return $result;
    }
}
