<?php

namespace App\Domain\Battle;

use App\Domain\Pokemon\Pokemon;

class Battle
{
    public function __construct(
        public Pokemon $pokemon1,
        public Pokemon $pokemon2,
        public ?int $turn = 1,
        public ?int $winnerId = null
    ) {}
}