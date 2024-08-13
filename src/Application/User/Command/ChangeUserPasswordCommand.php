<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\User\Dto\ChangeUserPasswordDtoInterface;
use App\Domain\User\Entity\UserInterface;

readonly class ChangeUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private ChangeUserPasswordDtoInterface $changePasswordDto,
        private UserInterface $user,
    ) {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getChangePasswordDto(): ChangeUserPasswordDtoInterface
    {
        return $this->changePasswordDto;
    }
}
