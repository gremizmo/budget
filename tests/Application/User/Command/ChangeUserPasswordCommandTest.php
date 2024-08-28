<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\Application\User\Command\ChangeUserPasswordCommand;
use App\Application\User\Dto\ChangeUserPasswordInputInterface;
use App\Infra\Http\Rest\User\Entity\User;
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
