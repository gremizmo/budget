<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Dto\EditUserDtoInterface;

interface EditUserFactoryInterface
{
    public function createFromDto(UserInterface $user, EditUserDtoInterface $editUserDto): UserInterface;
}
