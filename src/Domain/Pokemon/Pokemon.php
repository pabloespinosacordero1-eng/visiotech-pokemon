<?php

namespace App\Domain\Pokemon;

class Pokemon
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public int $level,
        public int $hpCurrent,
        public int $hpMax,
        public int $attack,
        public int $defense,
        public int $spAttack,
        public int $spDefense,
        public int $speed,
        /** @var Move[] */
        public array $moves = []
    ) {}
}