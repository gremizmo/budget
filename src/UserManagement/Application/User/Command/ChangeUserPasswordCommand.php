<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Command;

use App\UserManagement\Application\User\Dto\ChangeUserPasswordInputInterface;
use App\UserManagement\Domain\Shared\Command\CommandInterface;
use App\UserManagement\Domain\User\Model\UserInterface;

readonly class ChangeUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private ChangeUserPasswordInputInterface $changePasswordDto,
        private UserInterface $user,
    ) {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getChangePasswordDto(): ChangeUserPasswordInputInterface
    {
        return $this->changePasswordDto;
    }
}
