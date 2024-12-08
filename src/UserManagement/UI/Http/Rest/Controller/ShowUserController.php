<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\Controller;

use App\UserManagement\Domain\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user/{uuid}', name: 'app_user_show', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
class ShowUserController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(
        string $uuid,
        #[CurrentUser] UserInterface $currentUser
    ): JsonResponse {
        if ($uuid !== $currentUser->getUuid()) {
            throw new \Exception('An error occurred while getting a user in ShowUserController', Response::HTTP_FORBIDDEN);
        }

        return $this->json($currentUser, Response::HTTP_OK);
    }
}
