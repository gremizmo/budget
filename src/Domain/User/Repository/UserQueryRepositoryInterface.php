<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserInterface;

interface UserQueryRepositoryInterface
{
    /**
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?UserInterface;
}
