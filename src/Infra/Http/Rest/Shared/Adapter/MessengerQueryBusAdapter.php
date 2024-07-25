<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Envelope\Exception\QueryBusException;
use App\Domain\Shared\Adapter\MessengerQueryBusInterface;
use App\Domain\Shared\Query\QueryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class MessengerQueryBusAdapter implements MessengerQueryBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws QueryBusException
     */
    public function query(QueryInterface $query): object
    {
        try {
            return $this->messageBus->dispatch($query)->last(HandledStamp::class)->getResult();
        } catch (\Throwable $exception) {
            throw new QueryBusException(
                'An error occurred while querying the bus.',
                $exception->getCode(),
                $exception,
            );
        }
    }
}
