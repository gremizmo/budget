<?php

namespace App\EnvelopeManagement\Infrastructure\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class EnvelopeSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        if (!isset($encodedEnvelope['body'], $encodedEnvelope['headers']['type'])) {
            throw new MessageDecodingFailedException('Invalid message format: missing "body" or "headers.type".');
        }

        $type = $encodedEnvelope['headers']['type'];
        if (!class_exists($type)) {
            throw new MessageDecodingFailedException(sprintf('Message class "%s" does not exist.', $type));
        }

        // Decode the body
        $body = json_decode($encodedEnvelope['body'], true, 512, JSON_THROW_ON_ERROR);

        // Instantiate the message class
        $message = new $type(...$body);

        return new Envelope($message);
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        return [
            'body' => json_encode($message->toArray(), JSON_THROW_ON_ERROR),
            'headers' => [
                'type' => get_class($message),
            ],
        ];
    }
}
