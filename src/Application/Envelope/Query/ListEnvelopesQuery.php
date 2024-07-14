<?php

declare(strict_types=1);

namespace App\Application\Envelope\Query;

use App\Domain\Shared\Query\QueryInterface;

readonly class ListEnvelopesQuery implements QueryInterface
{
    public function __construct(private ?int $envelopeId = null)
    {
    }

    public function getEnvelopeId(): ?int
    {
        return $this->envelopeId;
    }
}
