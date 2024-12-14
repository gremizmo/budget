<?php

namespace App\EnvelopeManagement\ReadModels\Views;

final class EnvelopesPaginated implements EnvelopesPaginatedInterface
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
    #[\Override]
    public function getEnvelopes(): iterable
    {
        return $this->envelopes;
    }

    #[\Override]
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
