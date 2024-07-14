<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Shared\Adapter\MessengerCommandBusInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/envelope/{id}', name: 'app_envelope_delete', methods: ['DELETE'])]
class DeleteEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessengerCommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(Envelope $envelope): JsonResponse
    {
        try {
            $this->commandBus->execute(new DeleteEnvelopeCommand($envelope));
        } catch (\Throwable $e) {
            $this->logger->error('Failed to process Envelope delete request: '.$e->getMessage());

            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['message' => 'Envelope delete request received'], Response::HTTP_ACCEPTED);
    }
}
