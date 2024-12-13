<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Ports\Outbound;

use App\EnvelopeManagement\Domain\Ports\Inbound\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
