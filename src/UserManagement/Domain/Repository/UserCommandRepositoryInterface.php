<?php

namespace App\UserManagement\Domain\Repository;

use App\UserManagement\Domain\Model\UserInterface;

interface UserCommandRepositoryInterface
{
    public function save(UserInterface $user): void;

    public function delete(UserInterface $user): void;
}
