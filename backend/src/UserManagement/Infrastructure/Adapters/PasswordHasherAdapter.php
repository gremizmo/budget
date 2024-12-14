<?php

namespace App\UserManagement\Infrastructure\Adapters;

use App\UserManagement\Domain\Ports\Outbound\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final readonly class PasswordHasherAdapter implements PasswordHasherInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[\Override]
    public function hash(PasswordAuthenticatedUserInterface $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    #[\Override]
    public function verify(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }
}
