<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CommandBusAdapter implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws \Throwable
     * @throws ExceptionInterface
     */
    public function execute(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (\Throwable $exception) {
            if (null !== $exception->getPrevious()) {
                throw $exception->getPrevious();
            }
            throw $exception;
        }
    }
}
