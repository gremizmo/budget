<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Dto;

use App\UserManagement\Application\User\Dto\ChangeUserPasswordInput;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordDtoTest extends TestCase
{
    public function testChangeUserPasswordDto(): void
    {
        $oldPassword = 'oldPassword123';
        $newPassword = 'newPassword123';

        $dto = new ChangeUserPasswordInput($oldPassword, $newPassword);

        $this->assertSame($oldPassword, $dto->getOldPassword());
        $this->assertSame($newPassword, $dto->getNewPassword());
    }
}
