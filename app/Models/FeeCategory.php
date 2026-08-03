<?php

namespace App\Models;

use PDO;

class FeeCategory
{
    public ?int $id = null;
    public string $name;
    public string $description;

    public function save(PDO $pdo): bool
    {
        $sql = "INSERT INTO fee_categories (name, description) VALUES (:name, :description)";

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            'name'        => $this->name,
            'description' => $this->description,
        ]);

        if ($result) {
            $this->id = (int) $pdo->lastInsertId();
        }

        return $result;
    }
}
