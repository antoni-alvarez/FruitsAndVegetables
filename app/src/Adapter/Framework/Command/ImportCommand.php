<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Command;

use App\Adapter\Framework\Persistence\InMemoryFruitRepository;
use App\Adapter\Framework\Persistence\InMemoryVegetableRepository;
use App\Application\UseCase\FoodImport;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function is_string;

#[AsCommand(
    name: 'app:import',
    description: 'Import food from JSON file',
)]
class ImportCommand extends Command
{
    public function __construct(
        private readonly FoodImport $foodImport,
        private readonly LoggerInterface $logger,
        private readonly InMemoryFruitRepository $fruitRepository,
        private readonly InMemoryVegetableRepository $vegetableRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('filePath', InputArgument::REQUIRED, 'Path to the JSON data file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Importing food..');
        $this->logger->info('Importing food..');

        $filePath = $input->getArgument('filePath');

        if (!is_string($filePath)) {
            throw new InvalidArgumentException('Invalid file path');
        }

        try {
            $this->foodImport->execute($filePath);
        } catch (Throwable $t) {
            $io->error('Error importing food: ' . $t->getMessage());
            $this->logger->error('Error importing food: ' . $t->getMessage());

            return Command::FAILURE;
        }

        $fruits = $this->fruitRepository->list();
        $vegetables = $this->vegetableRepository->list();

        $io->section('🍎 Fruits imported:');
        $io->listing(array_map(fn($f) => sprintf('%d. %s', $f->id, $f->name), $fruits));

        $io->section('🥕 Vegetables imported:');
        $io->listing(array_map(fn($v) => sprintf('%d. %s', $v->id, $v->name), $vegetables));

        $io->success('Food import process finished successfully.');
        $this->logger->info('Food import process finished successfully.');

        return Command::SUCCESS;
    }
}
