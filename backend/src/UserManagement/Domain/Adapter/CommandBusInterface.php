<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Adapter;

use App\UserManagement\Domain\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
