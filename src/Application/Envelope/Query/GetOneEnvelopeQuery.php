<?php

declare(strict_types=1);

namespace App\Application\Envelope\Query;

use App\Domain\Shared\Query\QueryInterface;

readonly class GetOneEnvelopeQuery implements QueryInterface
{
    public function __construct(private int $envelopeId)
    {
    }

    public function getEnvelopeId(): int
    {
        return $this->envelopeId;
    }
}
