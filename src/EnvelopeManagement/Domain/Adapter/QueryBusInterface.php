<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Adapter;

use App\EnvelopeManagement\Domain\Query\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
