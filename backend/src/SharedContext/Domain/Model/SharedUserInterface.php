<?php

declare(strict_types=1);

namespace App\SharedContext\Domain\Model;

use App\EnvelopeManagement\Domain\Aggregate\UserInterface;

interface SharedUserInterface extends UserInterface
{
    public function getUuid(): string;

    public function getEmail(): string;
}
