<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Food;

use function array_map;
use function round;

final readonly class FoodUnitNormalizer
{
    /**
     * @param list<Food> $foodItems
     *
     * @return list<array{
     *     id: int,
     *     name: string,
     *     type: string,
     *     quantity: float,
     *     unit: string
     *  }>
     */
    public function convertToKilograms(array $foodItems): array
    {
        return array_map(fn (Food $food) => [
            'id' => $food->id,
            'name' => $food->name,
            'type' => $food->type,
            'quantity' => round($food->quantity / 1000),
            'unit' => 'kg',
        ], $foodItems);
    }
}
