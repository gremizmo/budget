<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Dto\CreateUserDto;

interface CreateUserFactoryInterface
{
    public function createFromDto(CreateUserDto $createUserDto): UserInterface;
}
