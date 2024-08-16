<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Service;

use App\Domain\User\Service\PasswordResetTokenGenerator;
use PHPUnit\Framework\TestCase;

class PasswordResetTokenGeneratorTest extends TestCase
{
    public function testGenerateReturnsString(): void
    {
        $generator = new PasswordResetTokenGenerator();
        $token = $generator->generate();

        $this->assertIsString($token);
    }

    public function testGenerateReturnsCorrectLength(): void
    {
        $generator = new PasswordResetTokenGenerator();
        $token = $generator->generate();

        $this->assertSame(64, \strlen($token));
    }
}
