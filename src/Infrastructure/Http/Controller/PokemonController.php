<?php

namespace App\Infrastructure\Http\Controller;

use App\Infrastructure\Persistence\MoveRepository;

class PokemonController
{
    public function __construct(
        private $repo,
        private MoveRepository $moveRepository
    ) {}

    public function list()
    {
        header('Content-Type: application/json');

        echo json_encode([
            'data' => $this->repo->findAll()
        ]);
    }

    public function show($id)
    {
        $move = $this->repo->findById($id);

        if (!$move) {
            http_response_code(404);
            echo json_encode(['error' => 'Pokemon not found']);
            return;
        }
        
        header('Content-Type: application/json');

        echo json_encode([
            'data' => $move
        ]);
    }

    public function create($payload)
    {
        header('Content-Type: application/json');

        $id = $this->repo->create([
            'name' => $payload['name'],
            'type' => $payload['type'],
            'level' => $payload['level'] ?? 1,
            'hp' => $payload['hp'],
            'attack' => $payload['attack'],
            'defense' => $payload['defense'],
        ]);

        echo json_encode([
            'id' => $id,
            'status' => 'created'
        ]);
    }

    public function update($id, array $payload)
    {
        $this->repo->update($id, $payload);

        echo json_encode(['status' => 'ok']);
    }

    public function delete($id)
    {
        $this->repo->delete($id);

        echo json_encode(['status' => 'ok']);
    }
        
    public function assignMove($pokemonId, array $payload): void
    {
        header('Content-Type: application/json');

        try {
            $moveId = $payload['move_id'] ?? null;

            if (!$moveId) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'move_id is required'
                ]);
                return;
            }

            // comprobar límite de 4 movimientos
            $currentMoves = $this->moveRepository->findByPokemonId($pokemonId);

            if (count($currentMoves) >= 4) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'Pokemon already has 4 moves'
                ]);
                return;
            }

            // asignar movimiento
            $this->moveRepository->assignToPokemon($pokemonId, $moveId);

            echo json_encode([
                'status' => 'assigned',
                'pokemon_id' => $pokemonId,
                'move_id' => $moveId
            ]);

        } catch (\Throwable $e) {
            http_response_code(500);

            echo json_encode([
                'error' => 'Internal server error'
            ]);
        }
    }

    public function getMoves($pokemonId)
    {
        header('Content-Type: application/json');

        echo json_encode([
            'data' => $this->moveRepository->findByPokemonId($pokemonId)
        ]);
    }

    public function getPossibleMoves($pokemonId)
    {
        header('Content-Type: application/json');

        echo json_encode([
            'data' => $this->moveRepository->findPossibleMoves($pokemonId)
        ]);
    }
}