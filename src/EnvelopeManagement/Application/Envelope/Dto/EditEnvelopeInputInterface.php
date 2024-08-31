<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Dto;

interface EditEnvelopeInputInterface
{
    public function getTitle(): string;

    public function getCurrentBudget(): string;

    public function getTargetBudget(): string;

    public function getParentId(): ?int;
}
