<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Dto\ResetUserPasswordDtoInterface;

readonly class ResetUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private ResetUserPasswordDtoInterface $resetUserPasswordDto,
        private UserInterface $user,
    ) {
    }

    public function getResetUserPasswordDto(): ResetUserPasswordDtoInterface
    {
        return $this->resetUserPasswordDto;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
