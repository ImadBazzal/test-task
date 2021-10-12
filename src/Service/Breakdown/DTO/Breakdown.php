<?php

declare(strict_types=1);


namespace App\Service\Breakdown\DTO;


use JetBrains\PhpStorm\Immutable;

#[Immutable] final class Breakdown
{
    public function __construct(
        public Expression $expression,
        public float $value
    )
    {
    }
}