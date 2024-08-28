<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Dto\ListEnvelopesInput;
use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\Shared\Model\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope', name: 'app_envelope_index', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
class ListEnvelopesController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        #[CurrentUser] UserInterface $user,
        #[MapQueryString] ListEnvelopesInput $listEnvelopesDto = new ListEnvelopesInput(),
    ): JsonResponse {
        try {
            $envelopes = $this->queryBus->query(new ListEnvelopesQuery($user, $listEnvelopesDto));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope listing request: '.$exception->getMessage());

            $exceptionType = \strrchr($exception::class, '\\');

            return $this->json([
                'error' => $exception->getMessage(),
                'type' => \substr(\is_string($exceptionType) ? $exceptionType : '', 1),
                'code' => $exception->getCode(),
            ], $exception->getCode());
        }

        return $this->json($envelopes, Response::HTTP_OK);
    }
}
