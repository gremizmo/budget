<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Ports\Outbound;

use App\UserManagement\Domain\Ports\Inbound\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
