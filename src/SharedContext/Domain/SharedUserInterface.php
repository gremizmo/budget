<?php

declare(strict_types=1);

namespace App\SharedContext\Domain;

use App\EnvelopeManagement\Domain\Envelope\Model\UserInterface;

interface SharedUserInterface extends UserInterface
{
    public function getId(): int;

    public function getEmail(): string;
}
