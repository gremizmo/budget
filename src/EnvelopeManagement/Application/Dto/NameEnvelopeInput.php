<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Dto;

final readonly class NameEnvelopeInput implements NameEnvelopeInputInterface
{
    public function __construct(
        public string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
