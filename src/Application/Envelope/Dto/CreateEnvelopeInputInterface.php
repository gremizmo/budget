<?php

declare(strict_types=1);

namespace App\Application\Envelope\Dto;

interface CreateEnvelopeInputInterface
{
    public function getTitle(): string;

    public function getCurrentBudget(): string;

    public function getTargetBudget(): string;

    public function getParentId(): ?int;
}
