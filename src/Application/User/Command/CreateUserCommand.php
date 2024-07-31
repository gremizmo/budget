<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Dto\CreateUserDto;
use App\Domain\Shared\Command\CommandInterface;

readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(private CreateUserDto $createUserDto)
    {
    }

    public function getCreateUserDto(): CreateUserDto
    {
        return $this->createUserDto;
    }
}
