<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Query;

use App\EnvelopeManagement\Domain\Envelope\Model\UserInterface;
use App\EnvelopeManagement\Domain\Shared\Query\QueryInterface;

readonly class ShowEnvelopeQuery implements QueryInterface
{
    public function __construct(private int $envelopeId, private UserInterface $user)
    {
    }

    public function getEnvelopeId(): int
    {
        return $this->envelopeId;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
