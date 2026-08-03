<?php

namespace Tests\Models;

use App\Database\DatabaseConnection;
use App\Models\FeeCategory;
use PHPUnit\Framework\TestCase;

class FeeCategoryTest extends TestCase
{
    private \PDO $pdo;

    protected function setUp(): void
    {
        $db = new DatabaseConnection('sqlite::memory:');
        $this->pdo = $db->getPdo();

        $this->pdo->exec('CREATE TABLE fee_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT NOT NULL
        )');
    }

    public function testCanCreateAndSaveFeeCategory(): void
    {
        $category = new FeeCategory();
        $category->name = 'Tuition Fee';
        $category->description = 'Standard tuition fee for the semester';

        $result = $category->save($this->pdo);

        $this->assertTrue($result);
        $this->assertNotNull($category->id);

        $stmt = $this->pdo->prepare('SELECT * FROM fee_categories WHERE id = :id');
        $stmt->execute(['id' => $category->id]);
        $savedData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals('Tuition Fee', $savedData['name']);
        $this->assertEquals('Standard tuition fee for the semester', $savedData['description']);
    }
}
