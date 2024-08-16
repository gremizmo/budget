<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\Application\User\Command\RequestPasswordResetCommand;
use App\Domain\User\Entity\UserInterface;
use PHPUnit\Framework\TestCase;

class RequestPasswordResetCommandTest extends TestCase
{
    public function testConstructorSetsUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $command = new RequestPasswordResetCommand($user);

        $this->assertSame($user, $command->getUser());
    }

    public function testGetUserReturnsUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $command = new RequestPasswordResetCommand($user);

        $this->assertInstanceOf(UserInterface::class, $command->getUser());
        $this->assertSame($user, $command->getUser());
    }
}
