<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\Dto\ResetUserPasswordInputInterface;
use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;

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
