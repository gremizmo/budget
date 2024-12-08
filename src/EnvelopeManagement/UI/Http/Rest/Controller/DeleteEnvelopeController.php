<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Controller;

use App\EnvelopeManagement\Application\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Application\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Adapter\CommandBusInterface;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\View\Envelope;
use App\SharedContext\Domain\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/{uuid}', name: 'app_envelope_delete', methods: ['DELETE'])]
#[IsGranted('ROLE_USER')]
class DeleteEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @throws EnvelopeNotFoundException
     */
    public function __invoke(
        string $uuid,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        $envelope = $this->queryBus->query(new ShowEnvelopeQuery($uuid, $user->getUuid()));
        if (!$envelope instanceof Envelope) {
            throw new EnvelopeNotFoundException('Envelope to delete does not exist for user', 404);
        }
        $this->commandBus->execute(new DeleteEnvelopeCommand($uuid, $user->getUuid()));

        return $this->json(['message' => 'Envelope delete request received'], Response::HTTP_OK);
    }
}
