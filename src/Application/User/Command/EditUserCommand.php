<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\Dto\EditUserInputInterface;
use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;

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
