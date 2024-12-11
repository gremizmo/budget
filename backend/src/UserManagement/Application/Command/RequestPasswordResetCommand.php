<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Command;

use App\UserManagement\Domain\Command\CommandInterface;
use App\UserManagement\Domain\Model\UserInterface;

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
