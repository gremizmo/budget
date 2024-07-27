<?php

declare(strict_types=1);

namespace App\Domain\Shared\Adapter;

use App\Domain\Shared\Query\QueryInterface;

interface MessengerQueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
