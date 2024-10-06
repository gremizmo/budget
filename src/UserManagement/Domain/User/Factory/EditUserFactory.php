<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\User\Factory;

use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Application\User\Dto\EditUserInputInterface;

readonly class EditUserFactory implements EditUserFactoryInterface
{
    public function __construct()
    {
    }

    public function createFromDto(UserInterface $user, EditUserInputInterface $editUserDto): UserInterface
    {
        return $user->setFirstname($editUserDto->getFirstname())
            ->setLastname($editUserDto->getLastname())
            ->setUpdatedAt(new \DateTime('now'));
    }
}
