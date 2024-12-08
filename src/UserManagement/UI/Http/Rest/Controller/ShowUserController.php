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

#[Route('/api/users/me', name: 'app_user_show', methods: ['GET'])]
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
        #[CurrentUser] UserInterface $currentUser
    ): JsonResponse {
        return $this->json($currentUser, Response::HTTP_OK);
    }
}
