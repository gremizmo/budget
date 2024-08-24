<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Dto\EditUserDtoInterface;
use App\Domain\User\Entity\UserInterface;

interface EditUserFactoryInterface
{
    public function createFromDto(UserInterface $user, EditUserDtoInterface $editUserDto): UserInterface;
}
