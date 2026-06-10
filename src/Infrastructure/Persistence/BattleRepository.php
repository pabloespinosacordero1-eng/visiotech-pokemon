<?php

namespace App\Infrastructure\Persistence;

use PDO;

class BattleRepository
{
    public function __construct(private \PDO $pdo) {}

    public function updateHp(int $id, int $hp): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE pokemon
            SET hp = :hp
            WHERE id = :id
        ");

        $stmt->execute([
            'hp' => $hp,
            'id' => $id
        ]);
    }
}