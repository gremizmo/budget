<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\User\Factory;

use App\UserManagement\Application\User\Dto\CreateUserInputInterface;
use App\UserManagement\Domain\Shared\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\User\Model\UserInterface;

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

    public function createFromDto(CreateUserInputInterface $createUserDto): UserInterface
    {
        $user = (new $this->userClass());
        $hashedPassword = $this->passwordHasher->hash($user, $createUserDto->getPassword());

        return $user->setFirstname($createUserDto->getFirstname())
            ->setLastname($createUserDto->getLastname())
            ->setEmail($createUserDto->getEmail())
            ->setPassword($hashedPassword)
            ->setConsentGiven($createUserDto->isConsentGiven())
            ->setRoles(['ROLE_USER']);
    }
}
