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

        $attacker = $this->pokemonRepository->findById($payload['attacker_id']);
        $defender = $this->pokemonRepository->findById($payload['defender_id']);
        $move     = $this->moveRepository->findById($payload['move_id']);

        $damage = $this->damageService->calculate($attacker, $defender, $move);

        $defender['hp_current'] -= $damage;

        if ($defender['hp_current'] < 0) {
            $defender['hp_current'] = 0;
        }

        $this->pokemonRepository->updateHp($defender['id'], $defender['hp_current']);

        echo json_encode([
            'damage' => $damage,
            'defender_hp' => $defender['hp_current'],
            'ko' => $defender['hp_current'] === 0
        ]);
    }
}