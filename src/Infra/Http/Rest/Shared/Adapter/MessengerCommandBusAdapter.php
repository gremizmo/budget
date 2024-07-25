<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Adapter\MessengerCommandBusInterface;
use App\Domain\Shared\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class MessengerCommandBusAdapter implements MessengerCommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function execute(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
