<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Application\User\Dto\CreateUserInput;
use App\Domain\Shared\Model\UserInterface;

interface CreateUserFactoryInterface
{
    public function createFromDto(CreateUserInput $createUserDto): UserInterface;
}
