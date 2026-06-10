<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Http\Controller\BattleController;
use App\Application\Service\DamageService;

class BattleControllerTest extends TestCase
{
    public function test_attack_returns_json()
    {
        $pokemonRepo = $this->createMock(\App\Infrastructure\Persistence\PokemonRepository::class);
        $moveRepo = $this->createMock(\App\Infrastructure\Persistence\MoveRepository::class);
        $damageService = new DamageService();

        $pokemonRepo->method('findById')
            ->willReturnOnConsecutiveCalls(
                [
                  'id' => 1,
                    'hp' => 100,
                    'level' => 10,
                    'attack' => 50,
                    'defense' => 30,
                    'type' => 'grass'
                ],
                [
                    'id' => 2,
                    'hp' => 100,
                    'level' => 10,
                    'attack' => 40,
                    'defense' => 20,
                    'type' => 'fire'
                ]
            );

        $moveRepo->method('findById')
            ->willReturn([
                'id' => 1,
                'power' => 40,
                'type' => 'fire'
            ]);

        $controller = new BattleController($pokemonRepo, $moveRepo, $damageService);

        $payload = [
            'attacker_id' => 1,
            'defender_id' => 2,
            'move_id' => 1
        ];

        ob_start();
        $controller->attack($payload);
        $output = ob_get_clean();

        $data = json_decode($output, true);

        $this->assertArrayHasKey('damage', $data);
        $this->assertArrayHasKey('defender_hp', $data);
        $this->assertArrayHasKey('ko', $data);
    }
}