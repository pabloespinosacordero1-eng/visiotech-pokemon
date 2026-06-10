<?php

namespace App\Infrastructure\Http\Controller;

use App\Infrastructure\Persistence\PokemonRepository;

class MoveController
{
    public function __construct(
        private $repo,
        private PokemonRepository $pokemonRepository
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
            echo json_encode(['error' => 'Move not found']);
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

        $id = $this->repo->create($payload);

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

    public function getPokemons($moveId)
    {
        header('Content-Type: application/json');

        echo json_encode([
            'data' => $this->pokemonRepository->findByMoveId($moveId)
        ]);
    }
}