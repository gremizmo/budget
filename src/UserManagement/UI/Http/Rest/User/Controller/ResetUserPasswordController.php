<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\User\Controller;

use App\UserManagement\Application\User\Command\ResetUserPasswordCommand;
use App\UserManagement\Application\User\Dto\ResetUserPasswordInput;
use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Domain\User\Adapter\CommandBusInterface;
use App\UserManagement\Domain\User\Adapter\QueryBusInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use App\UserManagement\UI\Http\Rest\User\Exception\ResetUserPasswordControllerException;
use App\UserManagement\UI\Http\Rest\User\Exception\UserPasswordResetTokenIsExpiredException;
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

    public function __invoke(#[MapRequestPayload] ResetUserPasswordInput $resetUserPasswordDto): JsonResponse
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
            throw new ResetUserPasswordControllerException(ResetUserPasswordControllerException::MESSAGE, $exception->getCode(), $exception);
        }

        return $this->json(['message' => 'Password was reset'], Response::HTTP_OK);
    }
}
