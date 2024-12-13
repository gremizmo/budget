<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Commands;

use App\UserManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class UpdateUserFirstnameCommand implements CommandInterface
{
    public function __construct(
        private string $uuid,
        private string $firstname,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }
}
