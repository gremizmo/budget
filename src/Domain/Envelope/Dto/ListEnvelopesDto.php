<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Dto;

final readonly class ListEnvelopesDto implements ListEnvelopesDtoInterface
{
    public function __construct(
        public ?int $id = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
