<?php

namespace App\Application\Service;

class DamageService
{
    public function calculate(array $attacker, array $defender, array $move): int
    {
        $random = rand(85, 100) / 100;

        $attack = $attacker['attack'];
        $defense = $defender['defense'];

        $base = (((2 * $attacker['level'] / 5 + 2) * $move['power'] * ($attack / $defense)) / 50) + 2;

        return (int) ($base * $random);
    }
}