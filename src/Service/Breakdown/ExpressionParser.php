<?php

declare(strict_types=1);


namespace App\Service\Breakdown;


use App\Service\Breakdown\DTO\Expression;
use App\Service\Breakdown\Enum\Unit;
use App\Service\Breakdown\Exception\InvalidExpression;

final class ExpressionParser
{
    public function parse(string $expression): Expression
    {
        $strlen = strlen($expression);

        if ($strlen === 0) {
            throw new InvalidExpression('Expression should not be empty');
        }

        if ($strlen === 1) {
            $unit  = $expression;
            $count = 1;
        } else {
            [$count, $unit] = str_split($expression, $strlen - 1);
        }

        if (!is_numeric($count) || $count <= 0) {
            throw new InvalidExpression('Unit count must be an integer more than 0');
        }

        if (!Unit::isValid($unit)) {
            throw new InvalidExpression('Unit is not valid');
        }

        return new Expression(Unit::from($unit), (int)$count);
    }
}