<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Application\User\Dto\EditUserInputInterface;
use App\Domain\Shared\Model\UserInterface;

interface EditUserFactoryInterface
{
    public function createFromDto(UserInterface $user, EditUserInputInterface $editUserDto): UserInterface;
}
