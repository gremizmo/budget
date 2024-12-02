<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Controller;

use App\EnvelopeManagement\Application\Command\NameEnvelopeCommand;
use App\EnvelopeManagement\Application\Dto\NameEnvelopeInput;
use App\EnvelopeManagement\Domain\Adapter\CommandBusInterface;
use App\SharedContext\Domain\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/{uuid}/name', name: 'app_envelope_name', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
class NameEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] NameEnvelopeInput $nameEnvelopeInput,
        string $uuid,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        $this->commandBus->execute(
            new NameEnvelopeCommand(
                $nameEnvelopeInput->getName(),
                $uuid,
                $user->getUuid(),
            ),
        );

        return $this->json(['message' => 'Envelope naming request received'], Response::HTTP_OK);
    }
}
