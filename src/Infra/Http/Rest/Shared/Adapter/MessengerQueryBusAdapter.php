<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Adapter\MessengerQueryBusInterface;
use App\Domain\Shared\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class MessengerQueryBusAdapter implements MessengerQueryBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function query(QueryInterface $query): object
    {
        try {
            return $this->messageBus->dispatch($query)->last(HandledStamp::class)->getResult();
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException('An error occurred while querying the bus.', 0, $e);
        }
    }
}
