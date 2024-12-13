<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Commands;

use App\UserManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class UpdateUserLastnameCommand implements CommandInterface
{
    public function __construct(
        private string $uuid,
        private string $lastName,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLastname(): string
    {
        return $this->lastName;
    }
}
