<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Repository\FoodRepositoryInterface;
use InvalidArgumentException;

use function mb_strtolower;
use function sprintf;

class FoodRepositoryProvider
{
    public function __construct(
        private readonly FoodRepositoryInterface $fruitRepository,
        private readonly FoodRepositoryInterface $vegetableRepository,
    ) {}

    public function forType(string $type): FoodRepositoryInterface
    {
        return match (mb_strtolower($type)) {
            'fruit' => $this->fruitRepository,
            'vegetable' => $this->vegetableRepository,
            default => throw new InvalidArgumentException(sprintf('Unknown food type: %s', $type)),
        };
    }
}
