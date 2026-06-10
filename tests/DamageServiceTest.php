<?php

use PHPUnit\Framework\TestCase;
use App\Application\Service\DamageService;

class DamageServiceTest extends TestCase
{
    public function test_damage_is_calculated()
    {
        $service = new DamageService();

        $attacker = [
            'level' => 10,
            'attack' => 50
        ];

        $defender = [
            'defense' => 30,
            'type' => 'grass'
        ];

        $move = [
            'power' => 40,
            'type' => 'fire'
        ];

        $damage = $service->calculate($attacker, $defender, $move);

        $this->assertIsNumeric($damage);
        $this->assertGreaterThan(0, $damage);
    }
}