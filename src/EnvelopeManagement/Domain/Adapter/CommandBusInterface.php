<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Adapter;

use App\EnvelopeManagement\Domain\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
