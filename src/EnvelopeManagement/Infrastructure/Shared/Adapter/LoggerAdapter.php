<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Shared\Adapter;

use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

readonly class LoggerAdapter implements LoggerInterface
{
    public function __construct(private PsrLoggerInterface $psrLogger)
    {
    }

    public function info(string $message, array $context = []): void
    {
        $this->psrLogger->info($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->psrLogger->warning($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->psrLogger->error($message, $context);
    }
}
