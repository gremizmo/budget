<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Dto;

use App\Domain\User\Dto\EditUserDto;
use PHPUnit\Framework\TestCase;

class EditUserDtoTest extends TestCase
{
    public function testEditUserDtoInstantiation(): void
    {
        $dto = new EditUserDto(
            firstname: 'John',
            lastname: 'Doe',
        );

        $this->assertInstanceOf(EditUserDto::class, $dto);
    }

    public function testGetFirstname(): void
    {
        $dto = new EditUserDto(
            firstname: 'John',
            lastname: 'Doe',
        );

        $this->assertEquals('John', $dto->getFirstname());
    }

    public function testGetLastname(): void
    {
        $dto = new EditUserDto(
            firstname: 'John',
            lastname: 'Doe',
        );

        $this->assertEquals('Doe', $dto->getLastname());
    }
}
