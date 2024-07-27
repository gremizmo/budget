<?php

declare(strict_types=1);

namespace App\Tests\Infra\Http\Rest\Shared\Adapter;

use App\Infra\Http\Rest\Shared\Adapter\LoggerAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class LoggerAdapterTest extends TestCase
{
    public function testInfo(): void
    {
        $psrLogger = $this->createMock(PsrLoggerInterface::class);
        $psrLogger->expects($this->once())
            ->method('info')
            ->with('test message', []);

        $adapter = new LoggerAdapter($psrLogger);
        $adapter->info('test message');
    }

    public function testWarning(): void
    {
        $psrLogger = $this->createMock(PsrLoggerInterface::class);
        $psrLogger->expects($this->once())
            ->method('warning')
            ->with('test message', []);

        $adapter = new LoggerAdapter($psrLogger);
        $adapter->warning('test message');
    }

    public function testError(): void
    {
        $psrLogger = $this->createMock(PsrLoggerInterface::class);
        $psrLogger->expects($this->once())
            ->method('error')
            ->with('test message', []);

        $adapter = new LoggerAdapter($psrLogger);
        $adapter->error('test message');
    }
}
