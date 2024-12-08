<?php

namespace App\UserManagement\Domain\Repository;

use App\UserManagement\Domain\Model\UserInterface;

interface UserQueryRepositoryInterface
{
    /**
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?UserInterface;
}
