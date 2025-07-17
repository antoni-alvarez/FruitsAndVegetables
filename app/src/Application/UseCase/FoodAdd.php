<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Service\FoodRepositoryProvider;
use App\Domain\Entity\Food;
use Psr\Log\LoggerInterface;

use function sprintf;

final readonly class FoodAdd
{
    public function __construct(
        private FoodRepositoryProvider $repositoryProvider,
        private LoggerInterface $logger,
    ) {}

    /**
     * @return array<int, Food>
     */
    public function execute(
        int $id,
        string $name,
        string $type,
        int $quantity,
        string $unit,
    ): void {
        $quantity = $unit === 'kg' ? $quantity * 1000 : $quantity;

        $food = new Food(
            $id,
            $name,
            $type,
            $quantity,
            'g',
        );

        $this->logger->info(sprintf('[FoodAdd] Storing new food item: %s', $name));

        $foodRepository = $this->repositoryProvider->forType($type);
        $foodRepository->add($food);
    }
}
