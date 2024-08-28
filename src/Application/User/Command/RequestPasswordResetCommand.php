<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;

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
