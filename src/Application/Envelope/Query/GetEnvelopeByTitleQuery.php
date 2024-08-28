<?php

declare(strict_types=1);

namespace App\Application\Envelope\Query;

use App\Domain\Shared\Model\UserInterface;
use App\Domain\Shared\Query\QueryInterface;

readonly class GetEnvelopeByTitleQuery implements QueryInterface
{
    public function __construct(private string $title, private UserInterface $user)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
