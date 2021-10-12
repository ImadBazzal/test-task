<?php

declare(strict_types=1);


namespace App\Repository;


use DateTimeInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

final class CacheBreakdownRepository implements BreakdownRepository
{
    public function __construct(private AdapterInterface $cache)
    {
    }

    public function persist(DateTimeInterface $t1, DateTimeInterface $t2, iterable $breakdowns): void
    {
        $item = $this->cache->getItem($this->buildKey($t1, $t2));

        $item->set($breakdowns);

        $this->cache->save($item);
    }

    public function find(DateTimeInterface $t1, DateTimeInterface $t2): iterable
    {
        $item = $this->cache->getItem($this->buildKey($t1, $t2));

        return $item->isHit() ? $item->get() : [];
    }

    private function buildKey(DateTimeInterface $t1, DateTimeInterface $t2): string
    {
        return "{$t1->getTimestamp()}-{$t2->getTimestamp()}";
    }
}