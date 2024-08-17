<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Repository;

use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Entity\EnvelopesPaginatedInterface;

interface EnvelopeQueryRepositoryInterface
{
    /**
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?EnvelopeInterface;

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): EnvelopesPaginatedInterface;
}
