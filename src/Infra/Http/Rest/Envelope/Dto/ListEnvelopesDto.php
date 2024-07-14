<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Dto;

use App\Domain\Envelope\Dto\ListEnvelopesDtoInterface;

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
