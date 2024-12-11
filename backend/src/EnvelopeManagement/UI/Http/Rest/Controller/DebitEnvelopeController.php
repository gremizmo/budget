<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Controller;

use App\EnvelopeManagement\Application\Command\DebitEnvelopeCommand;
use App\EnvelopeManagement\Domain\Adapter\CommandBusInterface;
use App\EnvelopeManagement\UI\Http\Dto\DebitEnvelopeInput;
use App\SharedContext\Domain\Model\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelopes/{uuid}/debit', name: 'app_envelope_dedit', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
final class DebitEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] DebitEnvelopeInput $debitEnvelopeInput,
        string $uuid,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        $this->commandBus->execute(
            new DebitEnvelopeCommand(
                $debitEnvelopeInput->getDebitMoney(),
                $uuid,
                $user->getUuid(),
            ),
        );

        return $this->json(['message' => 'Envelope debit request received'], Response::HTTP_OK);
    }
}
