<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Query\GetOneEnvelopeQuery;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Shared\Adapter\MessengerQueryBusInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/envelope/{id}', name: 'app_envelope_show', methods: ['GET'])]
class ShowEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessengerQueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(Envelope $envelope): JsonResponse
    {
        try {
            $envelope = $this->queryBus->query(new GetOneEnvelopeQuery($envelope->getId()));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope getOne request: '.$exception->getMessage());

            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return $this->json($envelope, Response::HTTP_OK);
    }
}
