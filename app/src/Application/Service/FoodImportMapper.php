<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\DTO\FoodDTO;

use function array_map;

/**
 * @phpstan-import-type FoodRawData from FoodImportParser
 * @phpstan-import-type FoodItem from FoodImportParser
 */
readonly class FoodImportMapper
{
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

        if ($item['unit'] === 'kg') {
            $quantity = $quantity * 1000;
        }

        return new FoodDTO(
            $item['id'],
            $item['name'],
            $item['type'],
            $quantity,
            'g',
        );
    }
}
