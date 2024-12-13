<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\ReadModels\Views;

interface EnvelopesPaginatedInterface
{
    /**
     * @return iterable<int, EnvelopeViewInterface>
     */
    public function getEnvelopes(): iterable;

    public function getTotalItems(): int;
}
