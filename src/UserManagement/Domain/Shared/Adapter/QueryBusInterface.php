<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Shared\Adapter;

use App\UserManagement\Domain\Shared\Query\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
