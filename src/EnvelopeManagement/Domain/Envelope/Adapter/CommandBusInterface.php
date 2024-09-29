<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Adapter;

use App\EnvelopeManagement\Domain\Envelope\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
