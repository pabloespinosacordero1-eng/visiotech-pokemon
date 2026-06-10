<?php

namespace App\Application\Service;

class DamageService
{
    public function calculate(array $attacker, array $defender, array $move): int
    {
        $level = $attacker['level'];
        $attack = $attacker['attack'];
        $defense = $defender['defense'];
        $power = $move['power'];

        $effectiveness = $this->getEffectiveness($move['type'], $defender['type']);

        $random = rand(85, 100) / 100;

        $damage =
            (((2 * $level / 5 + 2) * $attack * $power / $defense) / 50)
            * $effectiveness
            * $random;

        return (int) $damage;
    }

    private function getEffectiveness(string $attackType, string $defenseType): float
    {
        return $this->matrix()[$attackType][$defenseType] ?? 1;
    }

    private function matrix(): array
    {
        return [
            'fire' => ['grass' => 2, 'water' => 0.5, 'fire' => 1],
            'water' => ['fire' => 2, 'grass' => 0.5, 'water' => 1],
            'grass' => ['water' => 2, 'fire' => 0.5, 'grass' => 1],
        ];
    }    
}