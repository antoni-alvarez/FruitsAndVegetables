<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Persistence;

use App\Application\Service\FoodStorageFileHandler;
use App\Application\Service\FoodStorageMapper;
use App\Domain\Entity\Food;
use App\Domain\Repository\FoodRepositoryInterface;

use function array_values;

abstract class LocalStorageFoodRepository implements FoodRepositoryInterface
{
    /** @var array<string, Food> */
    private array $items = [];

    public function __construct(
        private readonly FoodStorageFileHandler $foodFileReader,
        private readonly FoodStorageMapper $foodStorageParser,
        private readonly string $filePath,
    ) {}

    public function add(Food $food): void
    {
        $this->load();
        $this->items[$food->name] = $food;
        $this->save();
    }

    public function remove(string $name): void
    {
        $this->load();
        unset($this->items[$name]);
        $this->save();
    }

    public function list(): array
    {
        $this->load();

        return array_values($this->items);
    }

    public function findByName(string $name): ?Food
    {
        $this->load();

        return $this->items[$name] ?? null;
    }

    private function load(): void
    {
        $rawFoodItems = $this->foodFileReader->read($this->filePath);

        $this->items = $this->foodStorageParser->parse($rawFoodItems);
    }

    private function save(): void
    {
        $array = $this->foodStorageParser->serialize($this->items);

        $this->foodFileReader->write($this->filePath, $array);
    }
}
