<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Application\User\Command\RequestPasswordResetCommand;
use App\Application\User\Dto\RequestPasswordResetInput;
use App\Application\User\Query\ShowUserQuery;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Infra\Http\Rest\User\Entity\User;
use Psr\Log\LoggerInterface;
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
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(#[MapRequestPayload] RequestPasswordResetInput $requestPasswordResetDto): JsonResponse
    {
        try {
            $user = $this->queryBus->query(new ShowUserQuery($requestPasswordResetDto->getEmail()));

            if ($user instanceof User) {
                $this->commandBus->execute(new RequestPasswordResetCommand($user));
            }
        } catch (\Exception $exception) {
            $this->logger->error(\sprintf('Failed to process Password reset request: %s', $exception->getMessage()));
            $exceptionType = \strrchr($exception::class, '\\');

            return $this->json([
                'error' => $exception->getMessage(),
                'type' => \substr(\is_string($exceptionType) ? $exceptionType : '', 1),
                'code' => $exception->getCode(),
            ], $exception->getCode());
        }

        return $this->json(['message' => 'Password reset email sent'], Response::HTTP_OK);
    }
}
