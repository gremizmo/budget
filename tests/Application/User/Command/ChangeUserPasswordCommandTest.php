<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\UserManagement\Application\User\Command\ChangeUserPasswordCommand;
use App\UserManagement\Application\User\Dto\ChangeUserPasswordInputInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordCommandTest extends TestCase
{
    public function testChangeUserPasswordCommand(): void
    {
        $changePasswordDto = $this->createMock(ChangeUserPasswordInputInterface::class);
        $user = $this->createMock(User::class);

        $command = new ChangeUserPasswordCommand($changePasswordDto, $user);

        $this->assertSame($changePasswordDto, $command->getChangePasswordDto());
        $this->assertSame($user, $command->getUser());
    }
}
