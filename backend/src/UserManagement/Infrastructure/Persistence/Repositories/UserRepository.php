<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Persistence\Repositories;

use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\Domain\Ports\Inbound\UserViewInterface;
use App\UserManagement\ReadModels\Views\UserView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserView>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($registry, UserView::class);
    }

    #[\Override]
    public function save(UserViewInterface $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    #[\Override]
    public function delete(UserViewInterface $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
