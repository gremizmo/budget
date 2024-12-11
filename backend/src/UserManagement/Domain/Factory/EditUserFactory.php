<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Factory;

use App\UserManagement\Application\Dto\EditUserInputInterface;
use App\UserManagement\Domain\Model\UserInterface;

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
