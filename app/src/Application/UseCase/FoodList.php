<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Service\FoodFetcher;
use App\Application\Service\FoodUnitNormalizer;
use App\Domain\DTO\FoodDTO;
use App\Domain\Entity\Food;
use Psr\Log\LoggerInterface;

use function count;
use function sprintf;

final readonly class FoodList
{
    public function __construct(
        private FoodFetcher $foodFetcher,
        private FoodUnitNormalizer $foodUnitNormalizer,
        private LoggerInterface $logger,
    ) {}

    /**
     * @return list<Food|FoodDTO>
     */
    public function execute(?string $type, ?string $unit): array
    {
        $this->logger->info('[FoodList] Fetching food items');

        $foodItems = $this->foodFetcher->fetch($type);

        if ($unit === 'kg') {
            $foodItems = $this->foodUnitNormalizer->convertToKilograms($foodItems);
        }

        $this->logger->info(sprintf('[FoodList] %s food items found', count($foodItems)));

        return $foodItems;
    }
}
