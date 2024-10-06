<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Adapter;

use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class QueryBusAdapter implements QueryBusInterface
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
