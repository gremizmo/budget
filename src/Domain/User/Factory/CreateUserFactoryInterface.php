<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Dto\CreateUserDto;
use App\Domain\User\Entity\UserInterface;

interface CreateUserFactoryInterface
{
    public function createFromDto(CreateUserDto $createUserDto): UserInterface;
}
