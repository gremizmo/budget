<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Command;

use App\UserManagement\Domain\User\Command\CommandInterface;
use App\UserManagement\Domain\User\Model\UserInterface;

readonly class RequestPasswordResetCommand implements CommandInterface
{
    public function __construct(
        private UserInterface $user,
    ) {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
