<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Command\EditEnvelopeCommand;
use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Domain\Envelope\Dto\EditEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Entity\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/{id}/edit', name: 'app_envelope_edit', methods: ['PUT'])]
#[IsGranted('ROLE_USER')]
class EditEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] EditEnvelopeDto $updateEnvelopeDto,
        int $id,
        #[CurrentUser] UserInterface $user,
    ): JsonResponse {
        try {
            $parentEnvelope = $updateEnvelopeDto->getParentId() ? $this->queryBus->query(
                new ShowEnvelopeQuery($updateEnvelopeDto->getParentId(), $user)
            ) : null;
            $envelope = $this->queryBus->query(new ShowEnvelopeQuery($id, $user));
            if (!$envelope instanceof Envelope) {
                $this->logger->error('Envelope does not exist for user');

                return $this->json(['error' => 'Envelope not found'], Response::HTTP_NOT_FOUND);
            }
            $this->commandBus->execute(
                new EditEnvelopeCommand(
                    $envelope,
                    $updateEnvelopeDto,
                    $parentEnvelope,
                )
            );
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope update request: '.$exception->getMessage());

            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return $this->json(['message' => 'Envelope update request received'], Response::HTTP_ACCEPTED);
    }
}
