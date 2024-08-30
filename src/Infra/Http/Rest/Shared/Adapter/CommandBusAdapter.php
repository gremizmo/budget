<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CommandBusAdapter implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws CommandBusAdapterException
     */
    public function execute(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (\Throwable $exception) {
            throw new CommandBusAdapterException(CommandBusAdapterException::MESSAGE, $exception->getCode(), $exception->getPrevious());
        }
    }
}
