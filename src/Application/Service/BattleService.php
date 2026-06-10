<?php

namespace App\Application\Service;

class BattleService
{
    public function __construct(
        private DamageService $damageService
    ) {}

    public function attack(Pokemon $attacker, Pokemon $defender, Move $move): Pokemon
    {
        $damage = $this->damageService->calculate($attacker, $defender, $move);

        $defender->hpCurrent -= $damage;

        if ($defender->hpCurrent < 0) {
            $defender->hpCurrent = 0;
        }

        return $defender;
    }
}