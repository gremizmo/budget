<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Application\Envelope\QueryHandler\ListEnvelopesQueryHandler;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Factory\EnvelopeCollectionFactory;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryHandlerTest extends TestCase
{
    private MockObject&EnvelopeQueryRepositoryInterface $envelopeQueryRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private EnvelopeCollectionFactory $envelopeCollectionFactory;

    private ListEnvelopesQueryHandler $listEnvelopesQueryHandler;

    protected function setUp(): void
    {
        $this->envelopeQueryRepositoryMock = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->envelopeCollectionFactory = new EnvelopeCollectionFactory();
        $this->listEnvelopesQueryHandler = new ListEnvelopesQueryHandler(
            $this->envelopeQueryRepositoryMock,
            $this->envelopeCollectionFactory,
            $this->loggerMock
        );
    }

    public function testInvokeSuccess(): void
    {
        $envelopes = [(new Envelope())->setTitle('Title test1'), (new Envelope())->setTitle('Title test2')];
        $query = new ListEnvelopesQuery();

        $this->envelopeQueryRepositoryMock->expects($this->once())
            ->method('findBy')
            ->willReturn($envelopes);

        $expectedResult = $this->envelopeCollectionFactory->create($envelopes)->toArray();

        $result = $this->listEnvelopesQueryHandler->__invoke($query)->toArray();

        array_map(function ($expectedEnvelope, $key) use ($result) {
            $this->assertEquals($expectedEnvelope->getTitle(), $result[$key]->getTitle());
        }, $expectedResult, array_keys($expectedResult));
    }

    public function testInvokeException(): void
    {
        $exception = new \Exception('Test Exception');
        $query = new ListEnvelopesQuery();

        $this->envelopeQueryRepositoryMock->method('findBy')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($this->equalTo('Test Exception'));

        $this->expectException(\Exception::class);

        $this->listEnvelopesQueryHandler->__invoke($query);
    }
}
