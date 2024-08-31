<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\User\Factory;

use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Application\User\Dto\CreateUserInput;

interface CreateUserFactoryInterface
{
    public function createFromDto(CreateUserInput $createUserDto): UserInterface;
}
