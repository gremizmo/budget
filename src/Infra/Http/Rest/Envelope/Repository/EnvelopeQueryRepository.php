<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Repository;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Envelope>
 */
class EnvelopeQueryRepository extends ServiceEntityRepository implements EnvelopeQueryRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Envelope::class);
    }

    /**
     * @throws \Exception
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Envelope
    {
        try {
            return parent::findOneBy($criteria);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        try {
            return parent::findBy($criteria, $orderBy, $limit, $offset);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
