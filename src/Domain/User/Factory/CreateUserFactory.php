<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Adapter\PasswordHasherInterface;
use App\Domain\User\Dto\CreateUserDto;

readonly class CreateUserFactory implements CreateUserFactoryInterface
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private string $userClass,
    ) {
        $model = new $userClass();
        if (!$model instanceof UserInterface) {
            throw new \RuntimeException('Class should be User in CreateUserFactory');
        }
    }

    public function createFromDto(CreateUserDto $createUserDto): UserInterface
    {
        $user = (new $this->userClass());

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
