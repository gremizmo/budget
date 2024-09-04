<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\User\Adapter;

use App\UserManagement\Domain\User\Query\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
