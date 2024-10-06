<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Command;

use App\UserManagement\Application\User\Dto\EditUserInputInterface;
use App\UserManagement\Domain\User\Command\CommandInterface;
use App\UserManagement\Domain\User\Model\UserInterface;

readonly class EditUserCommand implements CommandInterface
{
    public function __construct(
        private UserInterface $user,
        private EditUserInputInterface $updateUserDTO,
    ) {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getEditUserDTO(): EditUserInputInterface
    {
        return $this->updateUserDTO;
    }
}
