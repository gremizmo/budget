<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Query;

use App\EnvelopeManagement\Domain\Query\QueryInterface;

readonly class ShowEnvelopeQuery implements QueryInterface
{
    public function __construct(private string $envelopeUuid, private string $userUuid)
    {
    }

    public function getEnvelopeUuid(): string
    {
        return $this->envelopeUuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}
