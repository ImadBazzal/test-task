<?php

declare(strict_types=1);


namespace App\Service\Breakdown;


use App\Repository\BreakdownRepository;
use DateTimeImmutable;
use DateTimeInterface;

final class BreakdownService
{
    public const DATETIME_FORMAT = DateTimeInterface::RFC3339;

    public function __construct(
        private ExpressionParser $expressionParser,
        private BreakdownCalculator $breakdownCalculator,
        private BreakdownRepository $repository
    )
    {
    }

    public function getBreakdowns(string $t1, string $t2, iterable $expressions): iterable
    {
        $breakdowns = [];

        $t1 = $this->getDatetimeObject($t1);
        $t2 = $this->getDatetimeObject($t2);

        foreach ($expressions as $expression) {
            $breakdowns[$expression] = $this->breakdownCalculator->calculate(
                $t1,
                $t2,
                $this->expressionParser->parse($expression)
            );
        }

        $this->repository->persist($t1, $t2, $breakdowns);

        return $breakdowns;
    }

    public function getDatetimeObject(string $datetime): DateTimeInterface
    {
        return DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT, $datetime);
    }
}