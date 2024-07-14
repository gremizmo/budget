<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Repository;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
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
     * @throws \Exception
     */
    public function save(EnvelopeInterface $envelope): void
    {
        try {
            $this->em->persist($envelope);
            $this->em->flush();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function delete(EnvelopeInterface $envelope): void
    {
        try {
            $this->em->remove($envelope);
            $this->em->flush();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }
}
