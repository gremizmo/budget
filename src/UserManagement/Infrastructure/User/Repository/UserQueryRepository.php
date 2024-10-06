<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\User\Repository;

use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Repository;

class UserQueryRepository extends Repository implements UserQueryRepositoryInterface
{
    public function __construct(
        protected PaginatedFinderInterface $finder,
    ) {
        parent::__construct($finder);
    }

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @throws \Throwable
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?User
    {
        $query = new Query();
        $query->setQuery(new Query\Term($criteria));

        $result = $this->find($query, 1);

        $user = reset($result);

        return $user instanceof User ? $user : null;
    }
}
