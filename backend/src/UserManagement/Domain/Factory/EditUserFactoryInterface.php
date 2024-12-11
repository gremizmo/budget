<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Factory;

use App\UserManagement\Application\Dto\EditUserInputInterface;
use App\UserManagement\Domain\Model\UserInterface;

interface EditUserFactoryInterface
{
    public function createFromDto(UserInterface $user, EditUserInputInterface $editUserDto): UserInterface;
}
