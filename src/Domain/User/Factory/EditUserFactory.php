<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Dto\EditUserDtoInterface;
use App\Domain\User\Entity\UserInterface;

readonly class EditUserFactory implements EditUserFactoryInterface
{
    public function __construct()
    {
    }

    public function createFromDto(UserInterface $user, EditUserDtoInterface $editUserDto): UserInterface
    {
        $user->setFirstname($editUserDto->getFirstname())
            ->setLastname($editUserDto->getLastname())
            ->setUpdatedAt(new \DateTime('now'));

        return $user;
    }
}
