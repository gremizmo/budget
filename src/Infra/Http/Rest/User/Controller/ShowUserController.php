<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user/{id}', name: 'app_user_show', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
class ShowUserController extends AbstractController
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(
        User $user,
        #[CurrentUser] UserInterface $currentUser
    ): JsonResponse {
        if ($user->getId() !== $currentUser->getId()) {
            $this->logger->error('Failed to process User getOne request: User not allowed to access this resource');

            return $this->json([
                'error' => 'Failed to process User getOne request: User not allowed to access this resource',
            ], Response::HTTP_FORBIDDEN);
        }

        return $this->json($user, Response::HTTP_OK);
    }
}
