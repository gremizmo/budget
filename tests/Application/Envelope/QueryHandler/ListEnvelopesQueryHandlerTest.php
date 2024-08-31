<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\QueryHandler;

use App\BudgetManagement\Application\Envelope\Dto\ListEnvelopesInput;
use App\BudgetManagement\Application\Envelope\Query\ListEnvelopesQuery;
use App\BudgetManagement\Application\Envelope\QueryHandler\ListEnvelopesQueryHandler;
use App\BudgetManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\BudgetManagement\Domain\Envelope\Model\EnvelopesPaginated;
use App\BudgetManagement\Domain\Envelope\Model\EnvelopesPaginatedInterface;
use App\BudgetManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\BudgetManagement\Infrastructure\Http\Rest\Envelope\Entity\Envelope;
use App\UserManagement\Infrastructure\User\Entity\User;
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
                new ListEnvelopesQuery((new User())->setId(1), new ListEnvelopesInput()),
                [$envelope],
                new EnvelopesPaginated([$envelope], 1),
            ],
            'failure' => [
                new ListEnvelopesQuery((new User())->setId(2), new ListEnvelopesInput()),
                [],
                new EnvelopesPaginated([], 0),
            ],
        ];
    }
}
