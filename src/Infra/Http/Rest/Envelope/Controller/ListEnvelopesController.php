<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\Envelope\Dto\ListEnvelopesDto;
use App\Domain\Shared\Adapter\MessengerQueryBusInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/envelope', name: 'app_envelope_index', methods: ['GET'])]
class ListEnvelopesController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessengerQueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(#[MapRequestPayload] ListEnvelopesDto $listEnvelopesDto): JsonResponse
    {
        try {
            $envelope = $this->queryBus->query(new ListEnvelopesQuery($listEnvelopesDto->getId()));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope listing request: '.$exception->getMessage());

            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return $this->json($envelope, Response::HTTP_ACCEPTED);
    }
}
