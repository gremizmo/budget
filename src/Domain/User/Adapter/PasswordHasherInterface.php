<?php

namespace App\Domain\User\Adapter;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface PasswordHasherInterface
{
    public function hash(PasswordAuthenticatedUserInterface $user, string $password): string;

    public function verify(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool;
}
