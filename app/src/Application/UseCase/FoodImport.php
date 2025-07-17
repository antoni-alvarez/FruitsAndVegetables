<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Service\FoodImportMapper;
use App\Application\Service\FoodImportParser;
use App\Application\Service\FoodRepositoryProvider;
use App\Domain\Entity\Food;
use Psr\Log\LoggerInterface;

use function sprintf;

readonly class FoodImport
{
    public function __construct(
        private FoodImportParser $foodParser,
        private FoodImportMapper $foodMapper,
        private FoodRepositoryProvider $foodRepositoryProvider,
        private LoggerInterface $logger,
    ) {}

    public function execute(string $filePath): void
    {
        $rawData = $this->foodParser->parse($filePath);
        $foodItems = $this->foodMapper->mapArray($rawData);

        foreach ($foodItems as $foodItem) {
            $food = new Food($foodItem->id, $foodItem->name, $foodItem->quantity);

            $this->logger->info(sprintf('Food item added: %s', $food->name));

            $this->foodRepositoryProvider->forType($foodItem->type)->add($food);
        }
    }
}
