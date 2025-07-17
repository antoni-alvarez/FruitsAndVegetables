<?php

declare(strict_types=1);

namespace App\Application\Service;

use RuntimeException;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_array;
use function json_decode;
use function json_encode;
use function sprintf;

use const JSON_PRETTY_PRINT;

class FoodStorageFileHandler
{
    /**
     * @return array<mixed>
     */
    public function read(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $json = file_get_contents($filePath);

        if ($json === false) {
            throw new RuntimeException(sprintf('Could not read file at path: %s', $filePath));
        }

        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Invalid JSON format in file: %s', $filePath));
        }

        return $data;
    }

    /**
     * @param array<string, array{
     *      id: int,
     *      name: string,
     *      type: string,
     *      quantity: int,
     *      unit: string
     *   }> $data
     */
    public function write(string $filePath, array $data): void
    {
        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}
