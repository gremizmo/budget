<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Application\Envelope\QueryHandler\ListEnvelopesQueryHandler;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Factory\EnvelopeCollectionFactory;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryHandlerTest extends TestCase
{
    private MockObject&EnvelopeQueryRepositoryInterface $envelopeQueryRepositoryMock;
    private ListEnvelopesQueryHandler $listEnvelopesQueryHandler;

    protected function setUp(): void
    {
        $this->envelopeQueryRepositoryMock = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $envelopeCollectionFactory = new EnvelopeCollectionFactory();
        $this->listEnvelopesQueryHandler = new ListEnvelopesQueryHandler(
            $this->envelopeQueryRepositoryMock,
            $envelopeCollectionFactory,
        );
    }

    /**
     * @dataProvider envelopeDataProvider
     */
    public function testInvoke(ListEnvelopesQuery $query, array $envelopes, EnvelopeCollection $expectedCollection): void
    {
        $this->envelopeQueryRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['parent' => $query->getEnvelopeId()])
            ->willReturn($envelopes);

        $result = $this->listEnvelopesQueryHandler->__invoke($query);

        $this->assertEquals($expectedCollection, $result);
    }

    public function envelopeDataProvider(): array
    {
        $envelope = new Envelope();
        $envelope->setId(1);
        $envelopeCollection = new EnvelopeCollection([$envelope]);

        return [
            'success' => [
                new ListEnvelopesQuery(1),
                [$envelope],
                $envelopeCollection,
            ],
            'failure' => [
                new ListEnvelopesQuery(2),
                [],
                new EnvelopeCollection([]),
            ],
        ];
    }
}
