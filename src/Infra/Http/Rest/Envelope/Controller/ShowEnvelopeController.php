<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Infra\Http\Rest\Envelope\Exception\ShowEnvelopeControllerException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/{id}', name: 'app_envelope_show', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
class ShowEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        int $id,
        #[CurrentUser] UserInterface $user
    ): JsonResponse {
        try {
            $envelope = $this->queryBus->query(new ShowEnvelopeQuery($id, $user));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope show request: '.$exception->getMessage());

            throw new ShowEnvelopeControllerException(ShowEnvelopeControllerException::MESSAGE, $exception->getCode(), $exception);
        }

        return $this->json($envelope, Response::HTTP_OK);
    }
}
