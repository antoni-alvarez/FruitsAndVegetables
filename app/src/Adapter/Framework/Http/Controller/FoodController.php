<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Controller;

use App\Adapter\Framework\Http\Request\FoodFilterRequestDTO;
use App\Application\Service\FoodFetcher;
use App\Application\Service\FoodUnitConverter;
use App\Application\UseCase\FoodList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class FoodController extends AbstractController
{
    public function __construct(
        private readonly FoodList $foodList,
    ) {}

    #[Route('/food', name: 'List food', methods: [Request::METHOD_GET])]
    public function listFood(#[MapQueryString] FoodFilterRequestDTO $requestDTO): Response
    {
        $foodItems = $this->foodList->execute($requestDTO->type, $requestDTO->unit);

        return $this->json($foodItems);
    }
}
