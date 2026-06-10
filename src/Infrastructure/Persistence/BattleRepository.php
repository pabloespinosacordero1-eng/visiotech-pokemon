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
            SET hp_current = :hp
            WHERE id = :id
        ");

        $stmt->execute([
            'hp' => $hp,
            'id' => $id
        ]);
    }
}