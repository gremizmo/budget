<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Shared\Command\CommandInterface;
use App\Domain\User\Dto\CreateUserDtoInterface;

readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(private CreateUserDtoInterface $createUserDto)
    {
    }

    public function getCreateUserDto(): CreateUserDtoInterface
    {
        return $this->createUserDto;
    }
}
