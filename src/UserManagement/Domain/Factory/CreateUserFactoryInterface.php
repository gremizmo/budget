<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Factory;

use App\UserManagement\Application\Dto\CreateUserInputInterface;
use App\UserManagement\Domain\Model\UserInterface;

interface CreateUserFactoryInterface
{
    public function createFromDto(CreateUserInputInterface $createUserDto): UserInterface;
}
