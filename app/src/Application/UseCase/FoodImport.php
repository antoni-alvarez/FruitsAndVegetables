<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Service\FoodMapper;
use App\Application\Service\FoodParser;
use App\Application\Service\FoodRepositoryProvider;
use App\Domain\Entity\Food;
use Psr\Log\LoggerInterface;

use function sprintf;

readonly class FoodImport
{
    public function __construct(
        private FoodParser $foodParser,
        private FoodMapper $foodMapper,
        private FoodRepositoryProvider $foodRepositoryProvider,
        private LoggerInterface $logger,
    ) {}

    public function execute(string $filePath): void
    {
        $rawData = $this->foodParser->parse($filePath);
        $foodItems = $this->foodMapper->mapArray($rawData);

        foreach ($foodItems as $foodItem) {
            $food = new Food($foodItem->id, $foodItem->name, $foodItem->type, $foodItem->quantity);

            $this->logger->info(sprintf('Food item added: %s', $food->name));

            $this->foodRepositoryProvider->forType($foodItem->type)->add($food);
        }
    }
}
