<?php

declare(strict_types=1);


namespace App\Repository;


use DateTimeInterface;

interface BreakdownRepository
{
    public function persist(DateTimeInterface $t1, DateTimeInterface $t2, iterable $breakdowns);

    public function find(DateTimeInterface $t1, DateTimeInterface $t2): iterable;
}