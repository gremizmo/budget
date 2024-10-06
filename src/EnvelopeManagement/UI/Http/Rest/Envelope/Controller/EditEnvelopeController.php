<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Controller;

use App\EnvelopeManagement\Application\Envelope\Command\EditEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\Dto\EditEnvelopeInput;
use App\EnvelopeManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Envelope\Adapter\CommandBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use App\SharedContext\Domain\SharedUserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/{uuid}/edit', name: 'app_envelope_edit', methods: ['PUT'])]
#[IsGranted('ROLE_USER')]
class EditEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @throws EnvelopeNotFoundException
     */
    public function __invoke(
        #[MapRequestPayload] EditEnvelopeInput $updateEnvelopeDto,
        string $uuid,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        $parentEnvelope = $updateEnvelopeDto->getParentUuid() ? $this->queryBus->query(
            new ShowEnvelopeQuery($updateEnvelopeDto->getParentUuid(), $user->getUuid())
        ) : null;
        $envelope = $this->queryBus->query(new ShowEnvelopeQuery($uuid, $user->getUuid()));

        if (!$envelope instanceof Envelope) {
            $this->logger->error('Envelope does not exist for user');

            throw new EnvelopeNotFoundException('Envelope to edit does not exist for user', 404);
        }

        $this->commandBus->execute(
            new EditEnvelopeCommand(
                $envelope,
                $updateEnvelopeDto,
                $parentEnvelope instanceof Envelope ? $parentEnvelope : null,
            )
        );

        return $this->json(['message' => 'Envelope edit request received'], Response::HTTP_OK);
    }
}
