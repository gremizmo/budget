<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Controller;

use App\EnvelopeManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\SharedContext\Domain\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/{uuid}', name: 'app_envelope_show', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
class ShowEnvelopeController extends AbstractController
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
