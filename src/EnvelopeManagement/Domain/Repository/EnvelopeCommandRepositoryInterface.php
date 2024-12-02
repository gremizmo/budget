<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Repository;

use App\EnvelopeManagement\Domain\View\EnvelopeInterface;

interface EnvelopeCommandRepositoryInterface
{
    public function save(EnvelopeInterface $envelope): void;

    public function delete(EnvelopeInterface $envelope): void;
}
