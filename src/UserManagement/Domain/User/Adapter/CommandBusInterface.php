<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\User\Adapter;

use App\UserManagement\Domain\User\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
