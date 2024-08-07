<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Entity\UserInterface;
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
        #[CurrentUser] UserInterface $user,
    ): JsonResponse {
        try {
            $envelope = $this->queryBus->query(new ShowEnvelopeQuery($id, $user));
            if (!$envelope instanceof Envelope) {
                $this->logger->error('Envelope does not exist for user');

                return $this->json(['error' => EnvelopeNotFoundException::MESSAGE], Response::HTTP_NOT_FOUND);
            }
            $this->commandBus->execute(new DeleteEnvelopeCommand($envelope));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope delete request: '.$exception->getMessage());

            return $this->json([
                'error' => $exception->getMessage(),
                'type' => \substr(\strrchr($exception::class, '\\'), 1),
                'code' => $exception->getCode(),
            ], $exception->getCode());
        }

        return $this->json(['message' => 'Envelope delete request received'], Response::HTTP_ACCEPTED);
    }
}
