<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\User\Dto\ResetUserPasswordDtoInterface;
use App\Domain\User\Entity\User;

readonly class ResetUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private ResetUserPasswordDtoInterface $resetUserPasswordDto,
        private User $user,
    ) {
    }

    public function getResetUserPasswordDto(): ResetUserPasswordDtoInterface
    {
        return $this->resetUserPasswordDto;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
