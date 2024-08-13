<?php

namespace App\Domain\Envelope\Dto;

final readonly class ListEnvelopesDto implements ListEnvelopesDtoInterface
{
    public function __construct(
        private ?array $orderBy = null,
        private ?int $limit = null,
        private ?int $offset = null,
        private ?int $parentId = null,
    ) {
    }

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

    public function getParentId(): ?int
    {
        return $this->parentId;
    }
}
