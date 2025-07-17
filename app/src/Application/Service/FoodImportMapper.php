<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\DTO\FoodDTO;

use function array_map;

/**
 * @phpstan-import-type FoodRawData from FoodImportParser
 * @phpstan-import-type FoodItem from FoodImportParser
 */
class FoodImportMapper
{
    public function __construct() {}

    /**
     * @param array<int, FoodItem> $data
     *
     * @return array<int, FoodDTO>
     */
    public function mapArray(array $data): array
    {
        return array_map(fn ($item): FoodDTO => $this->mapItem($item), $data);
    }

    /**
     * @param FoodItem $item
     */
    public function mapItem(array $item): FoodDTO
    {
        $quantity = $item['quantity'];
        $unit = $item['unit'];

        if ($unit === 'kg') {
            $quantity = $quantity * 1000;
            $unit = 'g';
        }

        return new FoodDTO(
            $item['id'],
            $item['name'],
            $item['type'],
            $quantity,
            $unit,
        );
    }
}
