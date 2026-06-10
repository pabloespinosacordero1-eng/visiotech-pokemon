<?php

namespace App\Infrastructure\Http\Controller;

use App\Infrastructure\Persistence\PokemonRepository;
use App\Infrastructure\Persistence\MoveRepository;
use App\Application\Service\DamageService;

class BattleController
{
    public function __construct(
        private PokemonRepository $pokemonRepository,
        private MoveRepository $moveRepository,
        private DamageService $damageService
    ) {}

    public function attack(array $payload): void
    {
        header('Content-Type: application/json');

        if (!isset($payload['attacker_id'], $payload['defender_id'], $payload['move_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $attacker = $this->pokemonRepository->findById((int)$payload['attacker_id']);
        $defender = $this->pokemonRepository->findById((int)$payload['defender_id']);
        $move     = $this->moveRepository->findById((int)$payload['move_id']);

        if (!$attacker || !$defender || !$move) {
            http_response_code(404);
            echo json_encode(['error' => 'Pokemon or move not found']);
            return;
        }

        $damage = $this->damageService->calculate($attacker, $defender, $move);

        $defender['hp'] -= $damage;

        if ($defender['hp'] < 0) {
            $defender['hp'] = 0;
        }

        $this->pokemonRepository->updateHp($defender['id'], $defender['hp']);

        echo json_encode([
            'damage' => $damage,
            'defender_hp' => $defender['hp'],
            'ko' => $defender['hp'] === 0
        ]);
    }
}