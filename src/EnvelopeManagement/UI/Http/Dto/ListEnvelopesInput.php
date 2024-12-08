<?php

namespace App\EnvelopeManagement\UI\Http\Dto;

final readonly class ListEnvelopesInput implements ListEnvelopesInputInterface
{
    /**
     * @param array<string, string>|null $orderBy
     */
    public function __construct(
        private ?array $orderBy = null,
        private ?int $limit = null,
        private ?int $offset = null,
        private ?string $parentUuid = null,
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

    public function getParentUuid(): ?string
    {
        return $this->parentUuid;
    }
}
