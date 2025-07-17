<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCase;

use App\Application\Service\FoodImportMapper;
use App\Application\Service\FoodImportParser;
use App\Application\Service\FoodRepositoryProvider;
use App\Application\UseCase\FoodImport;
use App\Domain\DTO\FoodDTO;
use App\Domain\Entity\Food;
use App\Domain\Repository\FoodRepositoryInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use function sprintf;

final class FoodImportTest extends TestCase
{
    private readonly FoodImportParser&MockObject $foodImportParser;
    private readonly FoodImportMapper&MockObject $foodImportMapper;
    private readonly FoodRepositoryProvider&MockObject $foodRepositoryProvider;
    private readonly FoodRepositoryInterface&MockObject $foodRepository;
    private readonly LoggerInterface&MockObject $logger;
    private readonly FoodImport $foodImport;

    protected function setUp(): void
    {
        $this->foodImportParser = $this->createMock(FoodImportParser::class);
        $this->foodImportMapper = $this->createMock(FoodImportMapper::class);
        $this->foodRepositoryProvider = $this->createMock(FoodRepositoryProvider::class);
        $this->foodRepository = $this->createMock(FoodRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->foodImport = new FoodImport(
            $this->foodImportParser,
            $this->foodImportMapper,
            $this->foodRepositoryProvider,
            $this->logger,
        );
    }

    #[Test]
    #[DataProvider('dataProvider')]
    public function executeImportsSingleFoodCorrectly(array $rawData, FoodDTO $dto): void
    {
        $filePath = '/tmp/sample.csv';

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with(sprintf('Food item added: %s', $dto->name));

        $this->foodImportParser
            ->expects($this->once())
            ->method('parse')
            ->with($filePath)
            ->willReturn($rawData);

        $this->foodImportMapper
            ->expects($this->once())
            ->method('mapArray')
            ->with($rawData)
            ->willReturn([$dto]);

        $this->foodRepositoryProvider
            ->expects($this->once())
            ->method('forType')
            ->with($dto->type)
            ->willReturn($this->foodRepository);

        $this->foodRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(
                fn (Food $food) => $food->id === $dto->id
                    && $food->name === $dto->name
                    && $food->type === $dto->type
                    && $food->quantity === $dto->quantity
                    && $food->unit === $dto->unit,
            ));

        $this->foodImport->execute($filePath);
    }

    public static function dataProvider(): iterable
    {
        yield 'add fruit in grams' => [
            [['id' => 1, 'name' => 'Papaya', 'type' => 'fruit', 'quantity' => 1000, 'unit' => 'g']],
            new FoodDTO(1, 'Papaya', 'fruit', 1000, 'g'),
        ];

        yield 'add vegetable in kilograms' => [
            [['id' => 2, 'name' => 'Zucchini', 'type' => 'vegetable', 'quantity' => 2, 'unit' => 'kg']],
            new FoodDTO(2, 'Zucchini', 'vegetable', 2, 'kg'),
        ];
    }
}
