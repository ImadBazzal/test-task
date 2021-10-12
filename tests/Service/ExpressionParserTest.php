<?php

declare(strict_types=1);


namespace App\Tests\Service;


use App\Service\Breakdown\DTO\Expression;
use App\Service\Breakdown\Enum\Unit;
use App\Service\Breakdown\Exception\InvalidExpression;
use App\Service\Breakdown\ExpressionParser;
use PHPUnit\Framework\TestCase;

class ExpressionParserTest extends TestCase
{
    private ExpressionParser $expressionParser;

    public function setUp(): void
    {
        $this->expressionParser = new ExpressionParser();
    }

    /**
     * @dataProvider expressionProvider
     */
    public function testParsing(string $expression, Expression $expectedExpression)
    {
        $this->assertEquals($expectedExpression, $this->expressionParser->parse($expression));
    }

    public function expressionProvider(): iterable
    {
        yield '1 month' => ['1m', new Expression(Unit::MONTH(), 1)];
        yield '1 month short' => ['m', new Expression(Unit::MONTH(), 1)];
        yield '1 day' => ['1d', new Expression(Unit::DAY(), 1)];
        yield '1 day short' => ['d', new Expression(Unit::DAY(), 1)];
        yield '1 hour' => ['1h', new Expression(Unit::HOUR(), 1)];
        yield '1 hour short' => ['h', new Expression(Unit::HOUR(), 1)];
        yield '1 minute' => ['1i', new Expression(Unit::MINUTE(), 1)];
        yield '1 minute=>' => ['i', new Expression(Unit::MINUTE(), 1)];
        yield '1 second' => ['1s', new Expression(Unit::SECOND(), 1)];
        yield '1 second short' => ['s', new Expression(Unit::SECOND(), 1)];
    }

    /**
     * @dataProvider invalidExpressionProvider
     */
    public function testParsingException(string $expression, string $exceptionClass, string $errorMessage)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($errorMessage);

        $this->expressionParser->parse($expression);
    }

    public function invalidExpressionProvider(): iterable
    {
        yield 'empty' => ['', InvalidExpression::class, 'Expression should not be empty'];
        yield 'invalid count' => ['0m', InvalidExpression::class, 'Unit count must be an integer more than 0'];
        yield 'invalid unit' => ['1x', InvalidExpression::class, 'Unit is not valid'];
    }
}