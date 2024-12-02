<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Dto;

interface CreateEnvelopeInputInterface
{
    public function getUuid(): string;

    public function getName(): string;

    public function getTargetBudget(): string;
}
