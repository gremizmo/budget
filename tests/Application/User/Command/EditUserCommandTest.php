<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\Domain\Shared\Model\UserInterface;
use App\UserManagement\Application\User\Command\EditUserCommand;
use App\UserManagement\Application\User\Dto\EditUserInputInterface;
use PHPUnit\Framework\TestCase;

class EditUserCommandTest extends TestCase
{
    public function testCommandInstantiation(): void
    {
        $user = $this->createMock(UserInterface::class);
        $editUserDto = $this->createMock(EditUserInputInterface::class);
        $command = new EditUserCommand($user, $editUserDto);

        $this->assertInstanceOf(EditUserCommand::class, $command);
    }

    public function testGetUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $editUserDto = $this->createMock(EditUserInputInterface::class);
        $command = new EditUserCommand($user, $editUserDto);

        $this->assertSame($user, $command->getUser());
    }

    public function testGetEditUserDTO(): void
    {
        $user = $this->createMock(UserInterface::class);
        $editUserDto = $this->createMock(EditUserInputInterface::class);
        $command = new EditUserCommand($user, $editUserDto);

        $this->assertSame($editUserDto, $command->getEditUserDTO());
    }
}
