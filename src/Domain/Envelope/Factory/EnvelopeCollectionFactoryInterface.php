<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Entity\EnvelopeCollectionInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;

interface EnvelopeCollectionFactoryInterface
{
    /**
     * @param array<int, EnvelopeInterface> $collection
     */
    public function create(array $collection): EnvelopeCollectionInterface;
}
