<?php

namespace App\UserManagement\Domain\User\Repository;

use App\UserManagement\Domain\User\Model\UserInterface;

interface UserCommandRepositoryInterface
{
    public function save(UserInterface $user): void;

    public function delete(UserInterface $user): void;
}
