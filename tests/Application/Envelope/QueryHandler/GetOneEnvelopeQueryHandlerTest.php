<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\GetOneEnvelopeQuery;
use App\Application\Envelope\QueryHandler\GetOneEnvelopeQueryHandler;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetOneEnvelopeQueryHandlerTest extends TestCase
{
    private MockObject&EnvelopeQueryRepositoryInterface $envelopeQueryRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private GetOneEnvelopeQueryHandler $getOneEnvelopeQueryHandler;

    protected function setUp(): void
    {
        $this->envelopeQueryRepositoryMock = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->getOneEnvelopeQueryHandler = new GetOneEnvelopeQueryHandler($this->envelopeQueryRepositoryMock, $this->loggerMock);
    }

    public function testInvokeSuccess(): void
    {
        $envelopeId = 1;
        $envelope = new Envelope();
        $query = new GetOneEnvelopeQuery($envelopeId);

        $this->envelopeQueryRepositoryMock->method('findOneBy')
            ->willReturn($envelope);

        $result = $this->getOneEnvelopeQueryHandler->__invoke($query);

        $this->assertSame($envelope, $result);
    }

    public function testInvokeNotFound(): void
    {
        $this->envelopeQueryRepositoryMock->method('findOneBy')
            ->willReturn(null);

        $this->expectException(\Exception::class);

        $query = new GetOneEnvelopeQuery(1);
        $this->getOneEnvelopeQueryHandler->__invoke($query);
    }

    public function testInvokeException(): void
    {
        $exception = new \Exception('Test Exception');
        $this->envelopeQueryRepositoryMock->method('findOneBy')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($this->equalTo('Test Exception'));

        $this->expectException(\Exception::class);

        $query = new GetOneEnvelopeQuery(1);
        $this->getOneEnvelopeQueryHandler->__invoke($query);
    }
}
