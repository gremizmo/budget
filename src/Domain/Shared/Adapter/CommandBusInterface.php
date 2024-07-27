<?php

declare(strict_types=1);

namespace App\Domain\Shared\Adapter;

use App\Domain\Shared\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}
