<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

interface EnvelopeCollectionInterface
{
    /**
     * @return array<int, EnvelopeInterface>
     */
    public function toArray(): array;

    public function map(\Closure $func): EnvelopeCollectionInterface;

    public function filter(\Closure $p): EnvelopeCollectionInterface;

    public function reduce(\Closure $func, mixed $initial = null): mixed;

    /**
     * @return \Traversable<int, EnvelopeInterface>
     */
    public function getIterator(): \Traversable;

    public function count(): int;

    public function contains(EnvelopeInterface $element): bool;

    public function add(EnvelopeInterface $element): void;
}
