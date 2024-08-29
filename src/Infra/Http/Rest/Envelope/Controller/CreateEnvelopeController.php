<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Application\Envelope\Dto\CreateEnvelopeInput;
use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Infra\Http\Rest\Shared\Exception\CreateEnvelopeControllerException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/new', name: 'app_envelope_new', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
class CreateEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreateEnvelopeInput $createEnvelopeDto,
        #[CurrentUser] UserInterface $user,
    ): JsonResponse {
        try {
            $parentEnvelope = $createEnvelopeDto->getParentId() ? $this->queryBus->query(
                new ShowEnvelopeQuery($createEnvelopeDto->getParentId(), $user)
            ) : null;
            $this->commandBus->execute(
                new CreateEnvelopeCommand(
                    $createEnvelopeDto,
                    $user,
                    $parentEnvelope instanceof EnvelopeInterface ? $parentEnvelope : null,
                ),
            );

            return $this->json(['message' => 'Envelope creation request received'], Response::HTTP_OK);
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope creation request: '.$exception->getMessage());

            throw new CreateEnvelopeControllerException(CreateEnvelopeControllerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
