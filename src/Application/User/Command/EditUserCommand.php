<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Dto\EditUserDtoInterface;

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
