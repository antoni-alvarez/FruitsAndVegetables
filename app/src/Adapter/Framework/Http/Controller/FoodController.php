<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Controller;

use App\Application\Service\FoodRepositoryProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function array_merge;

class FoodController extends AbstractController
{
    public function __construct(
        private readonly FoodRepositoryProvider $repositoryProvider,
    ) {}

    #[Route('/food', name: 'List food')]
    public function getFood(): Response
    {
        $fruitRepository = $this->repositoryProvider->forType('fruit');
        $vegetablesRepository = $this->repositoryProvider->forType('vegetable');

        $fruits = $fruitRepository->list();
        $vegetables = $vegetablesRepository->list();

        return $this->json(array_merge($fruits, $vegetables));
    }
}
