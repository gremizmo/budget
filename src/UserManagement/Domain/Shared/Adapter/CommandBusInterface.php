<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Shared\Adapter;

use App\UserManagement\Domain\Shared\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
