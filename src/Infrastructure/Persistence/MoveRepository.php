<?php

namespace App\Infrastructure\Persistence;

use PDO;

class MoveRepository
{
    public function __construct(private \PDO $pdo) {}

    public function findAll(): array
    {
        return $this->pdo
            ->query("SELECT * FROM move")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM move
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO move (name, power, type)
            VALUES (:name, :power, :type)
        ");

        $stmt->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE move
            SET name = :name,
                type = :type,
                power = :power
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['type'],
            'power' => $data['power']
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM pokemon_moves
            WHERE move_id = :id
        ");

        $stmt->execute([
            'id' => $id,
        ]);

        $stmt = $this->pdo->prepare("
            DELETE FROM move
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
        ]);
    }

    public function findByPokemonId(int $pokemonId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT m.*
            FROM move m
            INNER JOIN pokemon_moves pm ON pm.move_id = m.id
            WHERE pm.pokemon_id = :id
        ");

        $stmt->execute(['id' => $pokemonId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findPossibleMoves(int $pokemonId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT m.*
            FROM move m
            JOIN pokemon p ON p.type = m.type
            WHERE p.id = :id
        ");

        $stmt->execute(['id' => $pokemonId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignToPokemon(int $pokemonId, int $moveId): void
    {   
        $stmt = $this->pdo->prepare("
            INSERT INTO pokemon_moves (pokemon_id, move_id)
            VALUES (:pokemon_id, :move_id)
        ");

        $stmt->execute([
            'pokemon_id' => $pokemonId,
            'move_id' => $moveId
        ]);
    }
}