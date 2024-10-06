<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Command;

use App\UserManagement\Application\User\Dto\ResetUserPasswordInputInterface;
use App\UserManagement\Domain\User\Command\CommandInterface;
use App\UserManagement\Domain\User\Model\UserInterface;

readonly class ResetUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private ResetUserPasswordInputInterface $resetUserPasswordDto,
        private UserInterface $user,
    ) {
    }

    public function getResetUserPasswordDto(): ResetUserPasswordInputInterface
    {
        return $this->resetUserPasswordDto;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
