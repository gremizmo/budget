<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Command;

use App\UserManagement\Application\User\Dto\CreateUserInputInterface;
use App\UserManagement\Domain\User\Command\CommandInterface;

readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(private CreateUserInputInterface $createUserDto)
    {
    }

    public function getCreateUserDto(): CreateUserInputInterface
    {
        return $this->createUserDto;
    }
}
