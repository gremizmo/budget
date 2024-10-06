<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Repository;

use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

interface EnvelopeCommandRepositoryInterface
{
    public function save(EnvelopeInterface $envelope): void;

    public function delete(EnvelopeInterface $envelope): void;
}
