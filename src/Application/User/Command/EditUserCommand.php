<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Dto\EditUserDtoInterface;
use App\Domain\User\Entity\UserInterface;
use App\Domain\Shared\Command\CommandInterface;

readonly class EditUserCommand implements CommandInterface
{
    public function __construct(
        private UserInterface $user,
        private EditUserDtoInterface $updateUserDTO,
    ) {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getEditUserDTO(): EditUserDtoInterface
    {
        return $this->updateUserDTO;
    }
}
