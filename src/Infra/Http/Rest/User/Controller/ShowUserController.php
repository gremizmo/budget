<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Domain\Shared\Model\UserInterface;
use App\Infra\Http\Rest\User\Exception\ShowUserControllerException;
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
        int $id,
        #[CurrentUser] UserInterface $currentUser
    ): JsonResponse {
        if ($id !== $currentUser->getId()) {
            $this->logger->error('Failed to process User getOne request: User not allowed to access this resource');
            throw new ShowUserControllerException(ShowUserControllerException::MESSAGE, Response::HTTP_FORBIDDEN);
        }

        return $this->json($currentUser, Response::HTTP_OK);
    }
}
