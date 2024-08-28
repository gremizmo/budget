<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\Dto\CreateUserInputInterface;
use App\Domain\Shared\Command\CommandInterface;

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
