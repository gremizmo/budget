<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

readonly class RequestPasswordResetCommand implements CommandInterface
{
    public function __construct(
        private User $user,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
