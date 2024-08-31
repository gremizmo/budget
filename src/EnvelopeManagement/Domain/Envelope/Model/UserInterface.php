<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Model;

interface UserInterface
{
    public function getId(): int;

    public function getEmail(): string;
}
