<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @template TKey of array-key
 * @template T
 *
 * @template-extends ArrayCollection<TKey, T>
 */
class EnvelopeCollection extends ArrayCollection implements EnvelopeCollectionInterface, Collection
{
    /**
     * @param array<TKey, T> $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct($elements);
    }

    /**
     * @template U
     *
     * @param \Closure(T): U $func
     */
    public function map(\Closure $func): EnvelopeCollectionInterface
    {
        return new self(parent::map($func)->toArray());
    }

    /**
     * @param \Closure(T, TKey): bool $p
     */
    public function filter(\Closure $p): EnvelopeCollectionInterface
    {
        return new self(parent::filter($p)->toArray());
    }

    /**
     * @param \Closure(T|null, T): T $func
     * @param T|null                 $initial
     *
     * @return T|null
     */
    public function reduce(\Closure $func, mixed $initial = null): mixed
    {
        return parent::reduce($func, $initial);
    }

    /**
     * @param T $element
     */
    public function add(mixed $element): void
    {
        parent::add($element);
    }

    /**
     * @return \Traversable<TKey, T>
     *
     * @throws \Exception
     */
    public function getIterator(): \Traversable
    {
        return parent::getIterator();
    }

    public function count(): int
    {
        return parent::count();
    }

    /**
     * @param T $element
     */
    public function contains(mixed $element): bool
    {
        return parent::contains($element);
    }

    /**
     * @return array<TKey, T>
     */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
