<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Controller;

use App\EnvelopeManagement\Application\Envelope\Command\EditEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\Dto\EditEnvelopeInput;
use App\EnvelopeManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\EnvelopeManagement\Domain\Shared\Adapter\CommandBusInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use App\EnvelopeManagement\UI\Http\Rest\Envelope\Exception\EditEnvelopeControllerException;
use App\SharedContext\Domain\SharedUserInterface;
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
        #[MapRequestPayload] EditEnvelopeInput $updateEnvelopeDto,
        int $id,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        try {
            $parentEnvelope = $updateEnvelopeDto->getParentId() ? $this->queryBus->query(
                new ShowEnvelopeQuery($updateEnvelopeDto->getParentId(), $user->getId())
            ) : null;
            $envelope = $this->queryBus->query(new ShowEnvelopeQuery($id, $user->getId()));
            if (!$envelope instanceof Envelope) {
                $this->logger->error('Envelope does not exist for user');

                return $this->json(['error' => EnvelopeNotFoundException::MESSAGE], Response::HTTP_NOT_FOUND);
            }
            $this->commandBus->execute(
                new EditEnvelopeCommand(
                    $envelope,
                    $updateEnvelopeDto,
                    $parentEnvelope instanceof Envelope ? $parentEnvelope : null,
                )
            );

            return $this->json(['message' => 'Envelope edit request received'], Response::HTTP_OK);
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope edition request: '.$exception->getMessage());

            throw new EditEnvelopeControllerException(EditEnvelopeControllerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}