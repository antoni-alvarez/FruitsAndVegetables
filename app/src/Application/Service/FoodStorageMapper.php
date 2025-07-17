<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Food;

use function array_map;

class FoodStorageMapper
{
    /**
     * @param array<string, array<string, string|int>> $rawFoodItems
     *
     * @return array<string, Food>
     */
    public function parse(array $rawFoodItems): array
    {
        return array_map(fn ($foodItem) => new Food(
            (int) $foodItem['id'],
            (string) $foodItem['name'],
            (int) $foodItem['weight'],
        ), $rawFoodItems);
    }

    /**
     * @param array<string, Food> $items
     *
     * @return array<string, array{
     *     id: int,
     *     name: string,
     *     weight: int
     *  }>
     */
    public function serialize(array $items): array
    {
        $result = [];

        foreach ($items as $name => $food) {
            $result[$name] = [
                'id' => $food->id,
                'name' => $food->name,
                'weight' => $food->weight,
            ];
        }

        return $result;
    }
}
