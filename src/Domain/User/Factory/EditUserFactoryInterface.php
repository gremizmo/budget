<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Dto\EditUserDto;
use App\Domain\User\Entity\UserInterface;

interface EditUserFactoryInterface
{
    public function updateUser(UserInterface $user, EditUserDto $updateUserDto): UserInterface;
}
