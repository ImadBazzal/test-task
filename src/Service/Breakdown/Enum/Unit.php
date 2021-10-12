<?php

declare(strict_types=1);


namespace App\Service\Breakdown\Enum;


use MyCLabs\Enum\Enum;

/**
 * @method static self MONTH
 * @method static self DAY
 * @method static self HOUR
 * @method static self MINUTE
 * @method static self SECOND
 */
final class Unit extends Enum
{
    private const MONTH = 'm';
    private const DAY = 'd';
    private const HOUR = 'h';
    private const MINUTE = 'i';
    private const SECOND = 's';
}