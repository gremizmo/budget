<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Dto;

interface ListEnvelopesInputInterface
{
    /**
     * @return array<string, string>|null
     */
    public function getOrderBy(): ?array;

    public function getLimit(): ?int;

    public function getOffset(): ?int;

    public function getParentUuid(): ?string;
}