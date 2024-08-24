<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Adapter\PasswordHasherInterface;
use App\Domain\User\Dto\CreateUserDto;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInterface;

readonly class CreateUserFactory implements CreateUserFactoryInterface
{
    public function __construct(private PasswordHasherInterface $passwordHasher)
    {
    }

    public function createFromDto(CreateUserDto $createUserDto): UserInterface
    {
        $user = new User();

        $hashedPassword = $this->passwordHasher->hash($user, $createUserDto->getPassword());

        $user->setFirstname($createUserDto->getFirstname())
            ->setLastname($createUserDto->getLastname())
            ->setEmail($createUserDto->getEmail())
            ->setPassword($hashedPassword)
            ->setConsentGiven($createUserDto->isConsentGiven())
            ->setRoles(['ROLE_USER']);

        return $user;
    }
}
