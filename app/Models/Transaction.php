<?php

namespace App\Models;

use PDO;

class Transaction
{
    public ?int $id = null;
    public int $student_id;
    public int $fee_category_id;
    public float $amount;
    public string $status = 'pending';

    public function save(PDO $pdo): bool
    {
        $sql = "INSERT INTO transactions (student_id, fee_category_id, amount, status) 
                VALUES (:student_id, :fee_category_id, :amount, :status)";

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            'student_id'      => $this->student_id,
            'fee_category_id' => $this->fee_category_id,
            'amount'          => $this->amount,
            'status'          => $this->status,
        ]);

        if ($result) {
            $this->id = (int) $pdo->lastInsertId();
        }

        return $result;
    }
}
