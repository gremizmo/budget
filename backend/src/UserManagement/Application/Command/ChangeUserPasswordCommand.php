<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Command;

use App\UserManagement\Application\Dto\ChangeUserPasswordInputInterface;
use App\UserManagement\Domain\Command\CommandInterface;
use App\UserManagement\Domain\Model\UserInterface;

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
