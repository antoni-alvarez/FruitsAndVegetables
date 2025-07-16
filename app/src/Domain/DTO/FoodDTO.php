<?php

declare(strict_types=1);

namespace App\Domain\DTO;

readonly class FoodDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public int $quantity,
        public string $unit,
    ) {}
}
