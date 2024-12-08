<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Dto;

interface CreateEnvelopeInputInterface
{
    public function getUuid(): string;

    public function getName(): string;

    public function getTargetBudget(): string;
}
