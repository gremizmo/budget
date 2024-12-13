<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Ports\Outbound;

use App\UserManagement\Domain\Ports\Inbound\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
