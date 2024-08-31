<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\Domain\Shared\Model\UserInterface;
use App\UserManagement\Application\User\Command\DeleteUserCommand;
use PHPUnit\Framework\TestCase;

class DeleteUserCommandTest extends TestCase
{
    public function testCommandInstantiation(): void
    {
        $user = $this->createMock(UserInterface::class);
        $command = new DeleteUserCommand($user);

        $this->assertInstanceOf(DeleteUserCommand::class, $command);
    }

    public function testGetUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $command = new DeleteUserCommand($user);

        $this->assertSame($user, $command->getUser());
    }
}
