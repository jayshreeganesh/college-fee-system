<?php

namespace App\Models;

use PDO;

class FeeCategory
{
    public ?int $id = null;
    public string $name;
    public float $amount;

    public function save(PDO $pdo): bool
    {
        $sql = "INSERT INTO fee_categories (name, amount) VALUES (:name, :amount)";

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            'name'   => $this->name,
            'amount' => $this->amount,
        ]);

        if ($result) {
            $this->id = (int) $pdo->lastInsertId();
        }

        return $result;
    }
}
