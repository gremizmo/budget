<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Shared\Adapter;

use App\EnvelopeManagement\Domain\Shared\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
