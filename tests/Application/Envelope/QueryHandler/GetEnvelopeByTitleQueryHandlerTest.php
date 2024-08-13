<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Application\Envelope\QueryHandler\GetEnvelopeByTitleQueryHandler;
use App\Application\Envelope\QueryHandler\ShowEnvelopeQueryHandler;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetEnvelopeByTitleQueryHandlerTest extends TestCase
{
    private EnvelopeQueryRepositoryInterface&MockObject $envelopeQueryRepository;
    private GetEnvelopeByTitleQueryHandler $handler;

    protected function setUp(): void
    {
        $this->envelopeQueryRepository = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->handler = new GetEnvelopeByTitleQueryHandler($this->envelopeQueryRepository);
    }

    public function testInvokeReturnsEnvelopeWhenItExists(): void
    {
        $title = 'Existing Title';
        $user = $this->createMock(UserInterface::class);
        $user->method('getId')->willReturn(1);
        $query = new GetEnvelopeByTitleQuery($title, $user);
        $envelope = $this->createMock(EnvelopeInterface::class);

        $this->envelopeQueryRepository->method('findOneBy')
            ->with(['title' => $title, 'user' => 1])
            ->willReturn($envelope);

        $result = ($this->handler)($query);

        $this->assertSame($envelope, $result);
    }

    public function testInvokeReturnsNullWhenEnvelopeDoesNotExist(): void
    {
        $title = 'Non-Existing Title';
        $user = $this->createMock(UserInterface::class);
        $user->method('getId')->willReturn(1);
        $query = new GetEnvelopeByTitleQuery($title, $user);

        $this->envelopeQueryRepository->method('findOneBy')
            ->with(['title' => $title, 'user' => 1])
            ->willReturn(null);

        $result = ($this->handler)($query);

        $this->assertNull($result);
    }
}
