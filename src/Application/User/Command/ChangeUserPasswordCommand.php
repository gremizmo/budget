<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\Dto\ChangeUserPasswordInputInterface;
use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;

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
