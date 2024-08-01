<?php

declare(strict_types=1);

namespace App\Application\Envelope\Query;

use App\Domain\Shared\Query\QueryInterface;
use App\Domain\User\Entity\UserInterface;

readonly class ListEnvelopesQuery implements QueryInterface
{
    public function __construct(private UserInterface $user, private ?int $envelopeId = null)
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getEnvelopeId(): ?int
    {
        return $this->envelopeId;
    }
}
