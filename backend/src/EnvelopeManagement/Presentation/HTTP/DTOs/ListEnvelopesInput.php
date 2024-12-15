<?php

namespace App\EnvelopeManagement\Presentation\HTTP\DTOs;

final readonly class ListEnvelopesInput
{
    /**
     * @param array<string, string>|null $orderBy
     */
    public function __construct(
        private ?array $orderBy = null,
        private ?int $limit = null,
        private ?int $offset = null,
    ) {
    }

    /**
     * @return array<string, string>|null
     */
    public function getOrderBy(): ?array
    {
        return $this->orderBy;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }
}
