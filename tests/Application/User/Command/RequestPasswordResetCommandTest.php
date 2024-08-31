<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\UserManagement\Application\User\Command\RequestPasswordResetCommand;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class RequestPasswordResetCommandTest extends TestCase
{
    public function testConstructorSetsUser(): void
    {
        $user = $this->createMock(User::class);
        $command = new RequestPasswordResetCommand($user);

        $this->assertSame($user, $command->getUser());
    }

    public function testGetUserReturnsUser(): void
    {
        $user = $this->createMock(User::class);
        $command = new RequestPasswordResetCommand($user);

        $this->assertInstanceOf(User::class, $command->getUser());
        $this->assertSame($user, $command->getUser());
    }
}
