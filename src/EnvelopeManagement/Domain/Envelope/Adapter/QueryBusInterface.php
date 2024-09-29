<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Adapter;

use App\EnvelopeManagement\Domain\Envelope\Query\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
