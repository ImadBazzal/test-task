<?php

declare(strict_types=1);


namespace App\Tests\Service;


use App\Service\Breakdown\BreakdownCalculator;
use App\Service\Breakdown\DTO\Expression;
use App\Service\Breakdown\Enum\Unit;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class BreakdownCalculatorTest extends TestCase
{
    private BreakdownCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new BreakdownCalculator();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCalculation(DateTimeInterface $t1, DateTimeInterface $t2, Expression $expression, float $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->calculator->calculate($t1, $t2, $expression));
    }

    public function dataProvider(): iterable
    {
        $t1 = DateTimeImmutable::createFromFormat(DateTimeInterface::ISO8601, '2021-01-01T00:00:00+00:00');
        $t2 = DateTimeImmutable::createFromFormat(DateTimeInterface::ISO8601, '2022-01-01T00:00:00+00:00');

        yield '1m' => [$t1, $t2, new Expression(Unit::MONTH(), 1), 12.];
        yield '2m' => [$t1, $t2, new Expression(Unit::MONTH(), 2), 6.];
        yield '4m' => [$t1, $t2, new Expression(Unit::MONTH(), 4), 3.];

        yield '1d' => [$t1, $t2, new Expression(Unit::DAY(), 1), 365.];
        yield '3d' => [$t1, $t2, new Expression(Unit::DAY(), 3), 121.67];
        yield '8d' => [$t1, $t2, new Expression(Unit::DAY(), 8), 45.63];

        yield '1h' => [$t1, $t2, new Expression(Unit::HOUR(), 1), 8760.];
        yield '100d' => [$t1, $t2, new Expression(Unit::HOUR(), 3), 2920.];
        yield '333d' => [$t1, $t2, new Expression(Unit::HOUR(), 8), 1095.];

        yield '1i' => [$t1, $t2, new Expression(Unit::MINUTE(), 1), 525600.];
        yield '100i' => [$t1, $t2, new Expression(Unit::MINUTE(), 3), 175200.];
        yield '333i' => [$t1, $t2, new Expression(Unit::MINUTE(), 8), 65700.];

        yield '1s' => [$t1, $t2, new Expression(Unit::SECOND(), 1), 31536000.];
        yield '1000s' => [$t1, $t2, new Expression(Unit::SECOND(), 3), 10512000.];
        yield '3333s' => [$t1, $t2, new Expression(Unit::SECOND(), 8), 3942000.];


        yield 'no diff' => [$t1, $t1, new Expression(Unit::SECOND(), 1), 0.];
    }
}