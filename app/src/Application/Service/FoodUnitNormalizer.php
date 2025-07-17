<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\DTO\FoodDTO;
use App\Domain\Entity\Food;

use function array_map;
use function round;

final readonly class FoodUnitNormalizer
{
    /**
     * @param list<Food> $foodItems
     *
     * @return list<FoodDTO>
     */
    public function convertToKilograms(array $foodItems): array
    {
        return array_map(function (Food $food): FoodDTO {
            $quantity = $food->unit === 'g' ? (int) round($food->quantity / 1000) : $food->quantity;

            return new FoodDTO(
                $food->id,
                $food->name,
                $food->type,
                $quantity,
                'kg',
            );
        }, $foodItems);
    }
}
