<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class FoodFilterRequestDTO
{
    public function __construct(
        #[Assert\Choice(['fruit', 'vegetable'], message: 'Invalid type')]
        public ?string $type = null,
        #[Assert\Choice(['kg', 'g'], message: 'Invalid unit')]
        public string $unit = 'g',
    ) {}
}
