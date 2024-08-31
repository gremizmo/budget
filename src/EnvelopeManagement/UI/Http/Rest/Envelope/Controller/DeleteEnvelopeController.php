<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Controller;

use App\EnvelopeManagement\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\EnvelopeManagement\Domain\Shared\Adapter\CommandBusInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use App\EnvelopeManagement\UI\Http\Rest\Envelope\Exception\DeleteEnvelopeControllerException;
use App\SharedContext\Domain\SharedUserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/{id}', name: 'app_envelope_delete', methods: ['DELETE'])]
#[IsGranted('ROLE_USER')]
class DeleteEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        int $id,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        try {
            $envelope = $this->queryBus->query(new ShowEnvelopeQuery($id, $user));
            if (!$envelope instanceof Envelope) {
                $this->logger->error('Envelope does not exist for user');

                throw new EnvelopeNotFoundException('Envelope to delete does not exist for user', 404);
            }
            $this->commandBus->execute(new DeleteEnvelopeCommand($envelope));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope delete request: '.$exception->getMessage());

            throw new DeleteEnvelopeControllerException(DeleteEnvelopeControllerException::MESSAGE, $exception->getCode(), $exception);
        }

        return $this->json(['message' => 'Envelope delete request received'], Response::HTTP_OK);
    }
}
