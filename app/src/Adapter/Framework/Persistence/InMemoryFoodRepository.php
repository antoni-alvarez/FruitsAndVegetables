<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Persistence;

use App\Domain\Entity\Food;
use App\Domain\Repository\FoodRepositoryInterface;

use function array_values;

abstract class InMemoryFoodRepository implements FoodRepositoryInterface
{
    /** @var array<string, Food> */
    private array $items = [];

    public function add(Food $food): void
    {
        $this->items[$food->name] = $food;
    }

    public function remove(string $name): void
    {
        unset($this->items[$name]);
    }

    public function list(): array
    {
        return array_values($this->items);
    }

    public function findByName(string $name): ?Food
    {
        return $this->items[$name] ?? null;
    }
}
