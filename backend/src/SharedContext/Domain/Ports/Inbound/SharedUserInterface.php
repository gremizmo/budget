<?php

declare(strict_types=1);

namespace App\SharedContext\Domain\Ports\Inbound;

use App\EnvelopeManagement\Domain\Ports\Inbound\UserInterface;

interface SharedUserInterface extends UserInterface
{
    public function getUuid(): string;

    public function getEmail(): string;
}
