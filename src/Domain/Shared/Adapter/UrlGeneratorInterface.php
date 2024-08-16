<?php

declare(strict_types=1);

namespace App\Domain\Shared\Adapter;

interface UrlGeneratorInterface
{
    public const ABSOLUTE_URL = 0;

    public function generate(string $route, array $parameters, int $referenceType): string;
}
