<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Controller;

use App\Adapter\Framework\Http\Request\FoodCreateRequestDTO;
use App\Adapter\Framework\Http\Request\FoodFilterRequestDTO;
use App\Application\UseCase\FoodAdd;
use App\Application\UseCase\FoodList;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

use function sprintf;

final class FoodController extends AbstractController
{
    public function __construct(
        private readonly FoodList $foodList,
        private readonly FoodAdd $foodAdd,
        private readonly LoggerInterface $logger,
    ) {}

    #[Route('/food', name: 'List food', methods: [Request::METHOD_GET])]
    public function listFood(#[MapQueryString] FoodFilterRequestDTO $request): Response
    {
        try {
            $foodItems = $this->foodList->execute($request->type, $request->unit);
        } catch (Throwable $t) {
            $this->logger->error(sprintf('[FoodController] Error fetching food items <%s>', $t->getMessage()));

            throw $t;
        }

        return $this->json($foodItems);
    }

    #[Route('/food', name: 'Add food item', methods: [Request::METHOD_POST])]
    public function addFood(#[MapRequestPayload] FoodCreateRequestDTO $request): Response
    {
        try {
            $this->foodAdd->execute($request->id, $request->name, $request->type, $request->quantity, $request->unit);
        } catch (Throwable $t) {
            $this->logger->error(sprintf('[FoodController] Error adding food item: <%s>', $t->getMessage()));

            throw $t;
        }

        return $this->json(Response::HTTP_CREATED);
    }
}
