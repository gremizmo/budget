<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\Shared\Adapter\MessengerQueryBusInterface;
use App\Infra\Http\Rest\Envelope\Dto\ListEnvelopesDto;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/envelope', name: 'app_envelope_index', methods: ['GET'])]
class ListEnvelopesController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessengerQueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(ListEnvelopesDto $listEnvelopesDto): JsonResponse
    {
        try {
            $envelope = $this->queryBus->query(new ListEnvelopesQuery($listEnvelopesDto->getId()));
        } catch (\Throwable $e) {
            $this->logger->error('Failed to process Envelope listing request: '.$e->getMessage());

            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($envelope, Response::HTTP_ACCEPTED);
    }
}
