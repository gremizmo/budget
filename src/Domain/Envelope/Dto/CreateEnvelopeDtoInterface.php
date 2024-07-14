<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Dto;

interface CreateEnvelopeDtoInterface
{
    public function getTitle(): string;

    public function getCurrentBudget(): string;

    public function getTargetBudget(): string;

    public function getParentId(): ?int;
}
