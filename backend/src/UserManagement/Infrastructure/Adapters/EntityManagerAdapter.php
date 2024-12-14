<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Adapters;

use App\UserManagement\Domain\Ports\Outbound\EntityManagerInterface;
use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManagerInterface;

final readonly class EntityManagerAdapter implements EntityManagerInterface
{
    public function __construct(private DoctrineEntityManagerInterface $entityManager)
    {
    }

    #[\Override]
    public function remove(object $object): void
    {
        $this->entityManager->remove($object);
    }

    #[\Override]
    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
