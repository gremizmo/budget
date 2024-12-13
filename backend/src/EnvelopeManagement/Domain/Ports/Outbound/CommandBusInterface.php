<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Ports\Outbound;

use App\EnvelopeManagement\Domain\Ports\Inbound\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
