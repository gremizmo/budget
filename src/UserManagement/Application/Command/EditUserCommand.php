<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Command;

use App\UserManagement\Application\Dto\EditUserInputInterface;
use App\UserManagement\Domain\Command\CommandInterface;
use App\UserManagement\Domain\Model\UserInterface;

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
