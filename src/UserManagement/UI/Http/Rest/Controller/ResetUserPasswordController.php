<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\Controller;

use App\UserManagement\Application\Command\ResetUserPasswordCommand;
use App\UserManagement\Application\Dto\ResetUserPasswordInput;
use App\UserManagement\Application\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Domain\Adapter\CommandBusInterface;
use App\UserManagement\Domain\Adapter\QueryBusInterface;
use App\UserManagement\Infrastructure\Entity\User;
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
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] ResetUserPasswordInput $resetUserPasswordDto): JsonResponse
    {
        $user = $this->queryBus->query(new GetUserByPasswordResetTokenQuery($resetUserPasswordDto->getToken()));

        if (!$user instanceof User) {
            throw new \Exception('User not found', Response::HTTP_NOT_FOUND);
        }

        if ($user->getPasswordResetTokenExpiry() > new \DateTimeImmutable()) {
            throw new \Exception('User password reset token is expired', Response::HTTP_UNAUTHORIZED);
        }

        $this->commandBus->execute(new ResetUserPasswordCommand($resetUserPasswordDto, $user));

        return $this->json(['message' => 'Password was reset'], Response::HTTP_OK);
    }
}
