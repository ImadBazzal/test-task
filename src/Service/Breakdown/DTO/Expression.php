<?php

declare(strict_types=1);


namespace App\Service\Breakdown\DTO;


use App\Service\Breakdown\Enum\Unit;
use App\Service\Breakdown\Exception\InvalidExpression;
use JetBrains\PhpStorm\Immutable;
use Stringable;

#[Immutable] final class Expression implements Stringable
{
    public function __construct(
        public Unit $unit,
        public int $count
    )
    {
        if ($this->count < 1) {
            throw new InvalidExpression('Unit value must be more than 0');
        }
    }

    public function __toString(): string
    {
        if ($this->count <= 1) {
            return (string) $this->unit->getValue();
        }

        return "{$this->count}{$this->unit->getValue()}";
    }
}
