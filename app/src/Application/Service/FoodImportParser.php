<?php

declare(strict_types=1);

namespace App\Application\Service;

use RuntimeException;

use function file_exists;
use function file_get_contents;
use function is_array;
use function json_decode;
use function sprintf;

/**
 * @phpstan-type FoodItem array{
 *      id: int,
 *      name: string,
 *      type: 'fruit'|'vegetable',
 *      quantity: int,
 *      unit: 'g'|'kg'
 * }
 * @phpstan-type FoodRawData array<int, FoodItem>
 */
readonly class FoodImportParser
{
    /**
     * @return FoodRawData
     */
    public function parse(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException(sprintf('File not found: %s', $filePath));
        }

        $json = file_get_contents($filePath);

        if (false === $json) {
            throw new RuntimeException(sprintf('Failed to read file: %s', $filePath));
        }

        /** @var FoodRawData|null $data */
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Invalid JSON format in file: %s', $filePath));
        }

        return $data;
    }
}
