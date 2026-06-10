<?php

namespace App\Domain\Move;

class Move
{
    public function __construct(
        public int $id,
        public string $name,
        public int $power,
        public string $type
    ) {}
}