<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

class EnvelopeCollection implements EnvelopeCollectionInterface, \Iterator
{
    private int $position;

    public function __construct(private iterable $elements = [])
    {
        $this->position = 0;
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function map(\Closure $func): EnvelopeCollectionInterface
    {
        return new self(array_map($func, $this->elements));
    }

    public function filter(\Closure $p): EnvelopeCollectionInterface
    {
        return new self(array_filter($this->elements, $p));
    }

    public function reduce(\Closure $func, $initial = null)
    {
        return array_reduce($this->elements, $func, $initial);
    }

    public function getIterator(): \Traversable
    {
        return new self($this->elements);
    }

    public function count(): int
    {
        return \count($this->elements);
    }

    public function contains(mixed $element): bool
    {
        return \in_array($element, $this->elements, true);
    }

    public function current(): mixed
    {
        return $this->elements[$this->position] ?? null;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->elements[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function add(EnvelopeInterface $element): self
    {
        $this->elements[] = $element;

        return $this;
    }
}
