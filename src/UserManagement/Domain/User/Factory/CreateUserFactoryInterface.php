<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\User\Factory;

use App\UserManagement\Application\User\Dto\CreateUserInputInterface;
use App\UserManagement\Domain\User\Model\UserInterface;

interface CreateUserFactoryInterface
{
    public function createFromDto(CreateUserInputInterface $createUserDto): UserInterface;
}
