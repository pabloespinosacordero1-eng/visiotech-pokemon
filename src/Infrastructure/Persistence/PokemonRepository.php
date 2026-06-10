<?php

namespace App\Infrastructure\Persistence;

use PDO;

class PokemonRepository
{
    public function __construct(private \PDO $pdo) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM pokemon");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM pokemon WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO pokemon (
                name, type, level,
                hp,
                attack, defense
            ) VALUES (
                :name, :type, :level,
                :hp,
                :attack, :defense
            )
        ");

        $stmt->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE pokemon
            SET name = :name,
                type = :type,
                level = :level,
                hp = :hp,
                attack = :attack,
                defense = :defense
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['type'],
            'level' => $data['level'],
            'hp' => $data['hp'],
            'attack' => $data['attack'],
            'defense' => $data['defense']                                    
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM pokemon_moves
            WHERE pokemon_id = :id
        ");

        $stmt->execute([
            'id' => $id,
        ]);

        $stmt = $this->pdo->prepare("
            DELETE FROM pokemon
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
        ]);
    }
        
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

    public function findByMoveId(int $moveId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*
            FROM pokemon p
            INNER JOIN pokemon_moves pm ON pm.pokemon_id = p.id
            WHERE pm.move_id = :id
        ");

        $stmt->execute(['id' => $moveId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}