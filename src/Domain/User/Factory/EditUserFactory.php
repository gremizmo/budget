<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Application\User\Dto\EditUserInputInterface;
use App\Domain\Shared\Model\UserInterface;

readonly class EditUserFactory implements EditUserFactoryInterface
{
    public function __construct()
    {
    }

    public function createFromDto(UserInterface $user, EditUserInputInterface $editUserDto): UserInterface
    {
        $user->setFirstname($editUserDto->getFirstname())
            ->setLastname($editUserDto->getLastname())
            ->setUpdatedAt(new \DateTime('now'));

        return $user;
    }
}
