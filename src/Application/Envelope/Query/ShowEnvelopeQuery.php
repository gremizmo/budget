<?php

declare(strict_types=1);

namespace App\Application\Envelope\Query;

use App\Domain\Shared\Query\QueryInterface;
use App\Domain\User\Entity\UserInterface;

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
