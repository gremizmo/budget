<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Repository;

use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Envelope>
 */
class EnvelopeCommandRepository extends ServiceEntityRepository implements EnvelopeCommandRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($registry, Envelope::class);
    }

    /**
     * @throws \Throwable
     */
    public function save(EnvelopeInterface $envelope): void
    {
        try {
            $this->em->persist($envelope);
            $this->em->flush();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new EnvelopeCommandRepositoryException(sprintf('%s on method save', EnvelopeCommandRepositoryException::MESSAGE), $exception->getCode(), $exception);
        }
    }

    /**
     * @throws \Throwable
     */
    public function delete(EnvelopeInterface $envelope): void
    {
        try {
            $this->em->remove($envelope);
            $this->em->flush();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new EnvelopeCommandRepositoryException(sprintf('%s on method delete', EnvelopeCommandRepositoryException::MESSAGE), $exception->getCode(), $exception->getPrevious());
        }
    }
}
