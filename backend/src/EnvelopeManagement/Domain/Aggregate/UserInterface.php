<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Aggregate;

interface UserInterface
{
    public function getUuid(): string;

    public function getEmail(): string;
}
