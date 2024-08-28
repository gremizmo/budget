<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Application\Envelope\QueryHandler\ShowEnvelopeQueryHandler;
use App\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Entity\User;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShowEnvelopeQueryHandlerTest extends TestCase
{
    private MockObject&EnvelopeQueryRepositoryInterface $envelopeQueryRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private ShowEnvelopeQueryHandler $getOneEnvelopeQueryHandler;

    protected function setUp(): void
    {
        $this->envelopeQueryRepositoryMock = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->getOneEnvelopeQueryHandler = new ShowEnvelopeQueryHandler($this->envelopeQueryRepositoryMock, $this->loggerMock);
    }

    /**
     * @dataProvider envelopeDataProvider
     */
    public function testInvoke(ShowEnvelopeQuery $query, ?Envelope $envelope, bool $shouldLogError): void
    {
        $this->envelopeQueryRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'id' => $query->getEnvelopeId(),
                'user' => $query->getUser()->getId(),
            ])
            ->willReturn($envelope);

        if ($shouldLogError) {
            $this->loggerMock->expects($this->once())
                ->method('error')
                ->with($this->stringContains(EnvelopeNotFoundException::MESSAGE));
            $this->expectException(EnvelopeNotFoundException::class);
        } else {
            $this->loggerMock->expects($this->never())
                ->method('error');
        }

        $result = $this->getOneEnvelopeQueryHandler->__invoke($query);

        if (!$shouldLogError) {
            $this->assertInstanceOf(Envelope::class, $result);
            $this->assertEquals($envelope?->getId(), $result->getId());
        }
    }

    /**
     * @return array{success:array{ShowEnvelopeQuery, Envelope, false}, failure:array{ShowEnvelopeQuery, null, true}}>
     */
    public function envelopeDataProvider(): array
    {
        $envelope = new Envelope();
        $envelope->setId(1);

        return [
            'success' => [
                new ShowEnvelopeQuery(1, (new User())->setId(1)),
                $envelope,
                false,
            ],
            'failure' => [
                new ShowEnvelopeQuery(2, (new User())->setId(2)),
                null,
                true,
            ],
        ];
    }
}
