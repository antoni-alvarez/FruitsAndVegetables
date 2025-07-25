<?php

declare(strict_types=1);

namespace App\Domain\Entity;

readonly class Food
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public int $quantity,
        public string $unit,
    ) {}
}
