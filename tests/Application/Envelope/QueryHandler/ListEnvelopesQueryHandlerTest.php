<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Application\Envelope\QueryHandler\ListEnvelopesQueryHandler;
use App\Domain\Envelope\Dto\ListEnvelopesDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Entity\EnvelopesPaginated;
use App\Domain\Envelope\Entity\EnvelopesPaginatedInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\User\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryHandlerTest extends TestCase
{
    private MockObject&EnvelopeQueryRepositoryInterface $envelopeQueryRepositoryMock;
    private ListEnvelopesQueryHandler $listEnvelopesQueryHandler;

    protected function setUp(): void
    {
        $this->envelopeQueryRepositoryMock = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->listEnvelopesQueryHandler = new ListEnvelopesQueryHandler(
            $this->envelopeQueryRepositoryMock,
        );
    }

    /**
     * @dataProvider envelopeDataProvider
     *
     * @param array<EnvelopeInterface> $envelopes
     */
    public function testInvoke(ListEnvelopesQuery $query, array $envelopes, EnvelopesPaginatedInterface $envelopesPaginated): void
    {
        $this->envelopeQueryRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with([
                'user' => $query->getUser()->getId(),
                'parent' => null,
            ])
            ->willReturn(new EnvelopesPaginated($envelopes, \count($envelopes)));

        $result = $this->listEnvelopesQueryHandler->__invoke($query);

        $this->assertEquals($envelopesPaginated, $result);
    }

    /**
     * @return array<mixed>
     */
    public function envelopeDataProvider(): array
    {
        $envelope = new Envelope();
        $envelope->setId(1);

        return [
            'success' => [
                new ListEnvelopesQuery((new User())->setId(1), new ListEnvelopesDto()),
                [$envelope],
                new EnvelopesPaginated([$envelope], 1),
            ],
            'failure' => [
                new ListEnvelopesQuery((new User())->setId(2), new ListEnvelopesDto()),
                [],
                new EnvelopesPaginated([], 0),
            ],
        ];
    }
}
