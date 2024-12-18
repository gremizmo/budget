<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\Controllers;

use App\EnvelopeManagement\Application\Queries\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Ports\Outbound\QueryBusInterface;
use App\SharedContext\Domain\Ports\Inbound\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelopes/{uuid}', name: 'app_envelope_show', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
final class ShowEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        string $uuid,
        #[CurrentUser] SharedUserInterface $user
    ): JsonResponse {
        return $this->json(
            $this->queryBus->query(new ShowEnvelopeQuery($uuid, $user->getUuid())),
            Response::HTTP_OK,
        );
    }
}
