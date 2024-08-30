<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Command\EditEnvelopeCommand;
use App\Application\Envelope\Dto\EditEnvelopeInput;
use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
use App\Infra\Http\Rest\Envelope\Exception\EditEnvelopeControllerException;
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
        #[CurrentUser] UserInterface $user,
    ): JsonResponse {
        try {
            $parentEnvelope = $updateEnvelopeDto->getParentId() ? $this->queryBus->query(
                new ShowEnvelopeQuery($updateEnvelopeDto->getParentId(), $user)
            ) : null;
            $envelope = $this->queryBus->query(new ShowEnvelopeQuery($id, $user));
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
