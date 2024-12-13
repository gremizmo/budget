<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Adapters;

use App\UserManagement\Domain\Ports\Inbound\QueryInterface;
use App\UserManagement\Domain\Ports\Outbound\QueryBusInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class QueryBusAdapter implements QueryBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function query(QueryInterface $query): mixed
    {
        return $this->messageBus->dispatch($query)->last(HandledStamp::class)?->getResult();
    }
}
