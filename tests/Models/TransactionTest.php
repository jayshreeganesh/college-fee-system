<?php

namespace Tests\Models;

use App\Database\DatabaseConnection;
use App\Models\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private \PDO $pdo;

    protected function setUp(): void
    {
        $db = new DatabaseConnection('sqlite::memory:');
        $this->pdo = $db->getPdo();

        $this->pdo->exec('CREATE TABLE transactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER NOT NULL,
            fee_category_id INTEGER NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            status TEXT NOT NULL DEFAULT \'pending\',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');
    }

    public function testCanCreateAndSaveTransaction(): void
    {
        $transaction = new Transaction();
        $transaction->student_id = 1;
        $transaction->fee_category_id = 2;
        $transaction->amount = 1500.50;
        $transaction->status = 'paid';

        $result = $transaction->save($this->pdo);

        $this->assertTrue($result);
        $this->assertNotNull($transaction->id);

        $stmt = $this->pdo->prepare('SELECT * FROM transactions WHERE id = :id');
        $stmt->execute(['id' => $transaction->id]);
        $savedData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals(1, $savedData['student_id']);
        $this->assertEquals(2, $savedData['fee_category_id']);
        $this->assertEquals(1500.50, (float) $savedData['amount']);
        $this->assertEquals('paid', $savedData['status']);
    }
}
