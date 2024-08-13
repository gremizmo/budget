<?php

namespace App\Infra\Http\Rest\User\Adapter;

use App\Domain\User\Adapter\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class PasswordHasherAdapter implements PasswordHasherInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hash(PasswordAuthenticatedUserInterface $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    public function verify(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }
}
