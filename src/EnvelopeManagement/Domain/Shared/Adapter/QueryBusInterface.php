<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Shared\Adapter;

use App\EnvelopeManagement\Domain\Shared\Query\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
