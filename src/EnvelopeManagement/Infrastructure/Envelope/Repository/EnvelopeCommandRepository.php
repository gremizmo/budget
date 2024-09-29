<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Repository;

use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Envelope>
 */
class EnvelopeCommandRepository extends ServiceEntityRepository implements EnvelopeCommandRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($registry, Envelope::class);
    }

    public function save(EnvelopeInterface $envelope): void
    {
        $this->em->persist($envelope);
        $this->em->flush();
    }

    public function delete(EnvelopeInterface $envelope): void
    {
        $this->em->remove($envelope);
        $this->em->flush();
    }
}
