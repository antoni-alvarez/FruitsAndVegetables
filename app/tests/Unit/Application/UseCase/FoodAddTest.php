<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCase;

use App\Application\Service\FoodRepositoryProvider;
use App\Application\UseCase\FoodAdd;
use App\Domain\Entity\Food;
use App\Domain\Repository\FoodRepositoryInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class FoodAddTest extends TestCase
{
    private readonly FoodRepositoryProvider&MockObject $repositoryProvider;
    private readonly FoodRepositoryInterface&MockObject $repository;
    private readonly LoggerInterface&MockObject $logger;
    private readonly FoodAdd $foodAdd;

    protected function setUp(): void
    {
        $this->repositoryProvider = $this->createMock(FoodRepositoryProvider::class);
        $this->repository = $this->createMock(FoodRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->foodAdd = new FoodAdd(
            $this->repositoryProvider,
            $this->logger,
        );
    }

    #[Test]
    #[DataProvider('dataProvider')]
    public function executeAddsFoodCorrectly(
        int $id,
        string $name,
        string $type,
        int $quantity,
        string $unit,
        int $expectedQuantityInGrams,
    ): void {
        $expectedFood = new Food($id, $name, $type, $expectedQuantityInGrams, 'g');

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with("[FoodAdd] Storing new food item: {$name}");

        $this->repositoryProvider
            ->expects($this->once())
            ->method('forType')
            ->with($type)
            ->willReturn($this->repository);

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($expectedFood);

        $this->foodAdd->execute($id, $name, $type, $quantity, $unit);
    }

    public static function dataProvider(): iterable
    {
        yield 'food in kilograms' => [
            1,
            'Mango',
            'fruit',
            2,
            'kg',
            2000,
        ];

        yield 'food in grams' => [
            2,
            'Spinach',
            'vegetable',
            500,
            'g',
            500,
        ];
    }
}
