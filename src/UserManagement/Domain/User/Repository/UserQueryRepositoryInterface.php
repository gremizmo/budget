<?php

namespace App\UserManagement\Domain\User\Repository;

use App\UserManagement\Domain\User\Model\UserInterface;

interface UserQueryRepositoryInterface
{
    /**
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?UserInterface;
}
