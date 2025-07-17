<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class FoodCreateRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $id,
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $name,
        #[Assert\Choice(['fruit', 'vegetable'])]
        public string $type,
        #[Assert\Positive]
        public int $quantity,
        #[Assert\Choice(['g', 'kg'])]
        public string $unit,
    ) {}
}
