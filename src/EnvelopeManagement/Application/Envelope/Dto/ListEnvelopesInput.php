<?php

namespace App\EnvelopeManagement\Application\Envelope\Dto;

final readonly class ListEnvelopesInput implements ListEnvelopesInputInterface
{
    /**
     * @param array<string, string>|null $orderBy
     */
    public function __construct(
        private ?array $orderBy = null,
        private ?int $limit = null,
        private ?int $offset = null,
        private ?int $parentId = null,
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

    public function getParentId(): ?int
    {
        return $this->parentId;
    }
}
