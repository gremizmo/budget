<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\User\Controller;

use App\UserManagement\Application\User\Command\ResetUserPasswordCommand;
use App\UserManagement\Application\User\Dto\ResetUserPasswordInput;
use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Domain\User\Adapter\CommandBusInterface;
use App\UserManagement\Domain\User\Adapter\QueryBusInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
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

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] ResetUserPasswordInput $resetUserPasswordDto): JsonResponse
    {
        $user = $this->queryBus->query(new GetUserByPasswordResetTokenQuery($resetUserPasswordDto->getToken()));

        if (!$user instanceof User) {
            $this->logger->error('Failed to process User resetPassword request: User not found');

            throw new \Exception('User not found', Response::HTTP_NOT_FOUND);
        }

        if ($user->getPasswordResetTokenExpiry() > new \DateTimeImmutable()) {
            $this->logger->error('Failed to process User resetPassword request: User password reset token is expired');

            throw new \Exception('User password reset token is expired', Response::HTTP_UNAUTHORIZED);
        }

        $this->commandBus->execute(new ResetUserPasswordCommand($resetUserPasswordDto, $user));

        return $this->json(['message' => 'Password was reset'], Response::HTTP_OK);
    }
}
