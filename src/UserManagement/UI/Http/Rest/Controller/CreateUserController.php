<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\Controller;

use App\UserManagement\Application\Command\CreateUserCommand;
use App\UserManagement\Application\Dto\CreateUserInput;
use App\UserManagement\Application\Query\GetUserAlreadyExistsQuery;
use App\UserManagement\Domain\Adapter\CommandBusInterface;
use App\UserManagement\Domain\Adapter\QueryBusInterface;
use App\UserManagement\Domain\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users/new', name: 'app_user_new', methods: ['POST'])]
class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] CreateUserInput $createUserDto): JsonResponse
    {
        if (!$this->queryBus->query(new GetUserAlreadyExistsQuery($createUserDto->getEmail())) instanceof UserInterface) {
            $this->commandBus->execute(new CreateUserCommand($createUserDto));
        } else {
            throw new \Exception('User already exists', Response::HTTP_CONFLICT);
        }

        return $this->json(['message' => 'User creation request received'], Response::HTTP_ACCEPTED);
    }
}
