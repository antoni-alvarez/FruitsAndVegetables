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
        $foodDTOS = $this->foodMapper->mapArray($rawData);

        foreach ($foodDTOS as $foodDTO) {
            $food = new Food($foodDTO->id, $foodDTO->name, $foodDTO->type, $foodDTO->quantity, $foodDTO->unit);

            $this->logger->info(sprintf('Food item added: %s', $food->name));

            $this->foodRepositoryProvider->forType($foodDTO->type)->add($food);
        }
    }
}
