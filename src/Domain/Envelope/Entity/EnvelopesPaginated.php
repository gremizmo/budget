<?php

namespace App\Domain\Envelope\Entity;

class EnvelopesPaginated implements EnvelopesPaginatedInterface
{
    private iterable $envelopes;
    private int $totalItems;

    public function __construct(iterable $envelopes, int $totalItems)
    {
        $this->envelopes = $envelopes;
        $this->totalItems = $totalItems;
    }

    public function getEnvelopes(): iterable
    {
        return $this->envelopes;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
