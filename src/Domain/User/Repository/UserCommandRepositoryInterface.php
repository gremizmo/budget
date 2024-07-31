<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserInterface;

interface UserCommandRepositoryInterface
{
    public function save(UserInterface $user): void;

    public function delete(UserInterface $user): void;
}
