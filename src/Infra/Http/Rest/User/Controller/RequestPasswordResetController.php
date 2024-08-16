<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Application\User\Command\RequestPasswordResetCommand;
use App\Application\User\Query\ShowUserQuery;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Dto\RequestPasswordResetDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user/request-reset-password', name: 'app_user_request_reset_password', methods: ['POST'])]
class RequestPasswordResetController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(#[MapRequestPayload] RequestPasswordResetDto $requestPasswordResetDto): JsonResponse
    {
        try {
            $this->commandBus->execute(
                new RequestPasswordResetCommand(
                    $this->queryBus->query(
                        new ShowUserQuery(
                            $requestPasswordResetDto->getEmail(),
                        ),
                    ),
                ),
            );
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
                'type' => \substr(\strrchr($exception::class, '\\'), 1),
                'code' => $exception->getCode(),
            ], $exception->getCode());
        }

        return $this->json(['message' => 'Password reset email sent'], Response::HTTP_OK);
    }
}
