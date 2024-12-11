<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Adapter;

use App\UserManagement\Domain\Query\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
