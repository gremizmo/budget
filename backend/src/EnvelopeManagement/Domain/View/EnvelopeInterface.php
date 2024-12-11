<?php

namespace App\EnvelopeManagement\Domain\View;

interface EnvelopeInterface
{
    public static function create(array $envelope): self;

    public function getUuid(): string;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;

    public function getCurrentBudget(): string;

    public function getTargetBudget(): string;

    public function getName(): string;

    public function getUserUuid(): string;

    public function isDeleted(): bool;
}
