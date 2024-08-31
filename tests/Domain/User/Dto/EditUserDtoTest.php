<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Dto;

use App\UserManagement\Application\User\Dto\EditUserInput;
use PHPUnit\Framework\TestCase;

class EditUserDtoTest extends TestCase
{
    public function testEditUserDtoInstantiation(): void
    {
        $dto = new EditUserInput(
            firstname: 'John',
            lastname: 'Doe',
        );

        $this->assertInstanceOf(EditUserInput::class, $dto);
    }

    public function testGetFirstname(): void
    {
        $dto = new EditUserInput(
            firstname: 'John',
            lastname: 'Doe',
        );

        $this->assertEquals('John', $dto->getFirstname());
    }

    public function testGetLastname(): void
    {
        $dto = new EditUserInput(
            firstname: 'John',
            lastname: 'Doe',
        );

        $this->assertEquals('Doe', $dto->getLastname());
    }
}
