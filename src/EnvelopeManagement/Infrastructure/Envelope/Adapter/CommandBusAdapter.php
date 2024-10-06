<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Adapter;

use App\EnvelopeManagement\Domain\Envelope\Adapter\CommandBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CommandBusAdapter implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function execute(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
