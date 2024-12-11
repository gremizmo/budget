<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Adapter;

use App\EnvelopeManagement\Domain\Adapter\PublisherInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

readonly class PublisherAdapter implements PublisherInterface
{
    private const DELIVERY_MODE_PERSISTENT = 2;

    public function __construct(private AMQPStreamConnection $amqpsStreamConnection)
    {
    }

    public function publishEvents(array $events): void
    {
        $channel = $this->amqpsStreamConnection->channel();
        $channel->exchange_declare('domain_events', 'fanout', false, true, false);

        foreach ($events as $event) {
            $messageBody = json_encode($event->toArray(), JSON_THROW_ON_ERROR);
            $headers = ['type' => get_class($event)];
            $message = new AMQPMessage($messageBody, [
                'content_type' => 'application/json',
                'delivery_mode' => self::DELIVERY_MODE_PERSISTENT,
            ]);
            $message->set('application_headers', new AMQPTable($headers));
            $channel->basic_publish($message, 'domain_events');
        }

        $channel->close();
    }
}
