<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Application\User\Command\ResetUserPasswordCommand;
use App\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Dto\ResetUserPasswordDto;
use App\Domain\User\Exception\UserPasswordResetTokenIsExpiredException;
use App\Infra\Http\Rest\User\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user/reset-password', name: 'app_user_reset_password', methods: ['POST'])]
class ResetUserPasswordController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(#[MapRequestPayload] ResetUserPasswordDto $resetUserPasswordDto): JsonResponse
    {
        try {
            $user = $this->queryBus->query(new GetUserByPasswordResetTokenQuery($resetUserPasswordDto->getToken()));

            if ($user instanceof User) {
                if ($user->getPasswordResetTokenExpiry() > new \DateTimeImmutable()) {
                    throw new UserPasswordResetTokenIsExpiredException(UserPasswordResetTokenIsExpiredException::MESSAGE, 401);
                }
                $this->commandBus->execute(new ResetUserPasswordCommand($resetUserPasswordDto, $user));
            }
        } catch (\Exception $exception) {
            $this->logger->error(\sprintf('Failed to process Password reset: %s', $exception->getMessage()));
            $exceptionType = \strrchr($exception::class, '\\');

            return $this->json([
                'error' => $exception->getMessage(),
                'type' => \substr(\is_string($exceptionType) ? $exceptionType : '', 1),
                'code' => $exception->getCode(),
            ], $exception->getCode());
        }

        return $this->json(['message' => 'Password was reset'], Response::HTTP_OK);
    }
}
