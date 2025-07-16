<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Food;

interface FoodRepositoryInterface
{
    public function add(Food $food): void;

    public function remove(string $name): void;

    /**
     * @return array<int, Food>
     */
    public function list(): array;

    public function findByName(string $name): ?Food;
}
