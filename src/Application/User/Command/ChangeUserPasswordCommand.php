<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\User\Dto\ChangeUserPasswordDtoInterface;
use App\Domain\User\Entity\User;

readonly class ChangeUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private ChangeUserPasswordDtoInterface $changePasswordDto,
        private User $user,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getChangePasswordDto(): ChangeUserPasswordDtoInterface
    {
        return $this->changePasswordDto;
    }
}
