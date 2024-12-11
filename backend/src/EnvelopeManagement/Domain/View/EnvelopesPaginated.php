<?php

namespace App\EnvelopeManagement\Domain\View;

class EnvelopesPaginated implements EnvelopesPaginatedInterface
{
    /** @var array<object> */
    private iterable $envelopes;
    private int $totalItems;

    /**
     * @param array<object> $envelopes
     */
    public function __construct(iterable $envelopes, int $totalItems)
    {
        $this->envelopes = $envelopes;
        $this->totalItems = $totalItems;
    }

    /**
     * @return array<object>
     */
    public function getEnvelopes(): iterable
    {
        return $this->envelopes;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
