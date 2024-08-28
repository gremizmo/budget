<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Repository;

use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Exception\UserCommandRepositoryException;
use App\Domain\User\Repository\UserCommandRepositoryInterface;
use App\Infra\Http\Rest\User\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserCommandRepository extends ServiceEntityRepository implements UserCommandRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws \Throwable
     */
    public function save(UserInterface $user): void
    {
        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new UserCommandRepositoryException(sprintf('%s on method save', UserCommandRepositoryException::MESSAGE), $exception->getCode(), $exception);
        }
    }

    /**
     * @throws \Throwable
     */
    public function delete(UserInterface $user): void
    {
        try {
            $this->em->remove($user);
            $this->em->flush();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new UserCommandRepositoryException(sprintf('%s on method delete', UserCommandRepositoryException::MESSAGE), $exception->getCode(), $exception);
        }
    }
}
