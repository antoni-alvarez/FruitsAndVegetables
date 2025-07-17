<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCase;

use App\Application\Service\FoodFetcher;
use App\Application\Service\FoodUnitNormalizer;
use App\Application\UseCase\FoodList;
use App\Domain\DTO\FoodDTO;
use App\Domain\Entity\Food;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class FoodListTest extends TestCase
{
    private readonly FoodFetcher&MockObject $foodFetcher;
    private readonly FoodUnitNormalizer&MockObject $foodUnitNormalizer;
    private readonly LoggerInterface&MockObject $logger;
    private readonly FoodList $foodList;

    protected function setUp(): void
    {
        $this->foodFetcher = $this->createMock(FoodFetcher::class);
        $this->foodUnitNormalizer = $this->createMock(FoodUnitNormalizer::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->foodList = new FoodList(
            $this->foodFetcher,
            $this->foodUnitNormalizer,
            $this->logger,
        );
    }

    #[Test]
    #[DataProvider('dataProvider')]
    public function testExecuteReturnsExpectedFoodList(
        ?string $type,
        ?string $unit,
        array $filteredItems,
        array $expected,
    ): void {
        $this->logger
            ->expects($this->atLeast(2))
            ->method('info');

        $this->foodFetcher
            ->expects($this->once())
            ->method('fetch')
            ->with($type)
            ->willReturn($filteredItems);

        if ($unit === 'kg') {
            $this->foodUnitNormalizer
                ->expects($this->once())
                ->method('convertToKilograms')
                ->with($filteredItems)
                ->willReturn($expected);
        } else {
            $this->foodUnitNormalizer
                ->expects($this->never())
                ->method('convertToKilograms');
        }

        $result = $this->foodList->execute($type, $unit);

        self::assertSame($expected, $result);
    }

    public static function dataProvider(): iterable
    {
        $apple = new Food(1, 'Apple', 'fruit', 1000, 'g');
        $banana = new Food(2, 'Banana', 'fruit', 500, 'g');
        $carrot = new Food(3, 'Carrot', 'vegetable', 2000, 'g');

        $appleDto = new FoodDTO(1, 'Apple', 'fruit', 1, 'kg');
        $bananaDto = new FoodDTO(2, 'Banana', 'fruit', 1, 'kg');
        $carrotDto = new FoodDTO(3, 'Carrot', 'vegetable', 2, 'kg');

        $allItems = [$apple, $banana, $carrot];

        yield 'no filter (g)' => [
            'type' => null,
            'unit' => 'g',
            'filteredItems' => $allItems,
            'expected' => $allItems,
        ];

        yield 'only fruit (g)' => [
            'type' => 'fruit',
            'unit' => 'g',
            'filteredItems' => [$apple, $banana],
            'expected' => [$apple, $banana],
        ];

        yield 'only vegetable (kg)' => [
            'type' => 'vegetable',
            'unit' => 'kg',
            'filteredItems' => [$carrot],
            'expected' => [$carrotDto],
        ];

        yield 'only fruit (kg)' => [
            'type' => 'fruit',
            'unit' => 'kg',
            'filteredItems' => [$apple, $banana],
            'expected' => [$appleDto, $bananaDto],
        ];
    }
}
