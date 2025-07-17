<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Controller;

use App\Adapter\Framework\Http\Request\FoodFilterRequestDTO;
use App\Application\Service\FoodFetcher;
use App\Application\Service\FoodUnitConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class FoodController extends AbstractController
{
    public function __construct(
        private readonly FoodFetcher $foodFetcher,
        private readonly FoodUnitConverter $foodUnitConverter,
    ) {}

    #[Route('/food', name: 'List food')]
    public function listFood(#[MapQueryString] FoodFilterRequestDTO $requestDTO): Response
    {
        $foodItems = $this->foodFetcher->fetch($requestDTO->type);

        if ($requestDTO->unit === 'kg') {
            $foodItems = $this->foodUnitConverter->convertToKilograms($foodItems);
        }

        return $this->json($foodItems);
    }
}
