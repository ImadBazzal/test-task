<?php

declare(strict_types=1);


namespace App\Service\Breakdown;


use App\Service\Breakdown\DTO\Expression;
use App\Service\Breakdown\Enum\Unit;
use App\Service\Breakdown\Exception\InvalidExpression;
use Carbon\Carbon;
use DateTimeInterface;

final class BreakdownCalculator
{
    private const PRECISION = 2;

    public function calculate(DateTimeInterface $t1, DateTimeInterface $t2, Expression $expression): float
    {
        $diff = match ($expression->unit->getKey()) {
            Unit::SECOND()->getKey() => Carbon::create($t1)->diffInSeconds($t2),
            Unit::MINUTE()->getKey() => Carbon::create($t1)->diffInMinutes($t2),
            Unit::HOUR()->getKey() => Carbon::create($t1)->diffInHours($t2),
            Unit::DAY()->getKey() => Carbon::create($t1)->diffInDays($t2),
            Unit::MONTH()->getKey() => Carbon::create($t1)->diffInMonths($t2),
            default => throw new InvalidExpression('Unsupported unit')
        };

        if ($diff === 0 || $expression->count === 0) {
            return 0.;
        }

        return round($diff / $expression->count, self::PRECISION);
    }
}