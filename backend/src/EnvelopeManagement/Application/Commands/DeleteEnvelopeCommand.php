<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Commands;

use App\EnvelopeManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class DeleteEnvelopeCommand implements CommandInterface
{
    public function __construct(private string $uuid, private string $userUuid)
    {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}
