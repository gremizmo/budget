<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\Controllers;

use App\UserManagement\Application\Commands\RequestUserPasswordResetCommand;
use App\UserManagement\Domain\Ports\Outbound\CommandBusInterface;
use App\UserManagement\Presentation\HTTP\DTOs\RequestUserPasswordResetInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users/request-password-reset', name: 'app_user_request_password_reset', methods: ['POST'])]
final class RequestUserPasswordResetController extends AbstractController
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] RequestUserPasswordResetInput $requestPasswordResetDto): JsonResponse
    {
        $this->commandBus->execute(new RequestUserPasswordResetCommand($requestPasswordResetDto->getEmail()));

        return $this->json(['message' => 'Password reset email sent'], Response::HTTP_OK);
    }
}
