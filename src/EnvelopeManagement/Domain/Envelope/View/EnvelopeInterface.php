<?php

namespace App\EnvelopeManagement\Domain\Envelope\View;

interface EnvelopeInterface
{
    public static function createFromQueryRepository(array $dataFromDatabase): self;
}
