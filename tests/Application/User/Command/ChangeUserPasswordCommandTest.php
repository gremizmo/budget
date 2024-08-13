<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\Application\User\Command\ChangeUserPasswordCommand;
use App\Domain\User\Dto\ChangeUserPasswordDtoInterface;
use App\Domain\User\Entity\UserInterface;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordCommandTest extends TestCase
{
    public function testChangeUserPasswordCommand(): void
    {
        $changePasswordDto = $this->createMock(ChangeUserPasswordDtoInterface::class);
        $user = $this->createMock(UserInterface::class);

        $command = new ChangeUserPasswordCommand($changePasswordDto, $user);

        $this->assertSame($changePasswordDto, $command->getChangePasswordDto());
        $this->assertSame($user, $command->getUser());
    }
}
