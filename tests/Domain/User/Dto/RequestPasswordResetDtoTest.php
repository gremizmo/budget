<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Dto;

use App\Domain\User\Dto\RequestPasswordResetDto;
use PHPUnit\Framework\TestCase;

class RequestPasswordResetDtoTest extends TestCase
{
    public function testConstructorSetsEmail(): void
    {
        $email = 'user@example.com';
        $dto = new RequestPasswordResetDto($email);

        $this->assertSame($email, $dto->getEmail());
    }

    public function testGetEmailReturnsEmail(): void
    {
        $email = 'user@example.com';
        $dto = new RequestPasswordResetDto($email);

        $this->assertSame($email, $dto->getEmail());
    }
}
