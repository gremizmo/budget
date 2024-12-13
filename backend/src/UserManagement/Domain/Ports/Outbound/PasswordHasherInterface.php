<?php

namespace App\UserManagement\Domain\Ports\Outbound;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface PasswordHasherInterface
{
    public function hash(PasswordAuthenticatedUserInterface $user, string $password): string;

    public function verify(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool;
}
