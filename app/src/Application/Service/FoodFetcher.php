<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Food;

use function array_merge;

final readonly class FoodFetcher
{
    public function __construct(
        private FoodRepositoryProvider $repositoryProvider,
    ) {}

    /**
     * @return array<int, Food>
     */
    public function fetch(?string $type): array
    {
        if ($type === 'fruit') {
            return $this->repositoryProvider->forType('fruit')->list();
        }

        if ($type === 'vegetable') {
            return $this->repositoryProvider->forType('vegetable')->list();
        }

        return array_merge(
            $this->repositoryProvider->forType('fruit')->list(),
            $this->repositoryProvider->forType('vegetable')->list(),
        );
    }
}
