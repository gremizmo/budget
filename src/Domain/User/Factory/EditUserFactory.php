<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Dto\EditUserDto;
use App\Domain\User\Entity\UserInterface;

readonly class EditUserFactory implements EditUserFactoryInterface
{
    public function __construct()
    {
    }

    public function updateUser(UserInterface $user, EditUserDto $updateUserDto): UserInterface
    {
        $user->setFirstname($updateUserDto->getFirstname())
            ->setLastname($updateUserDto->getLastname())
            ->setEmail($updateUserDto->getEmail())
            ->setPassword($updateUserDto->getPassword())
            ->setUpdatedAt(new \DateTime('now'));

        return $user;
    }
}
