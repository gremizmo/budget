<?php

declare(strict_types=1);

namespace App\Tests\Infra\Http\Rest\Shared\Adapter;

use App\Infra\Http\Rest\Shared\Adapter\UrlGeneratorAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyUrlGeneratorInterface;

class UrlGeneratorAdapterTest extends TestCase
{
    private SymfonyUrlGeneratorInterface&MockObject $symfonyUrlGenerator;
    private UrlGeneratorAdapter $urlGeneratorAdapter;

    protected function setUp(): void
    {
        $this->symfonyUrlGenerator = $this->createMock(SymfonyUrlGeneratorInterface::class);
        $this->urlGeneratorAdapter = new UrlGeneratorAdapter($this->symfonyUrlGenerator);
    }

    public function testGenerate(): void
    {
        $route = 'app_user_reset_password';
        $parameters = ['token' => 'reset-token'];
        $referenceType = SymfonyUrlGeneratorInterface::ABSOLUTE_URL;
        $expectedUrl = 'https://example.com/reset-password?token=reset-token';

        $this->symfonyUrlGenerator->expects($this->once())
            ->method('generate')
            ->with($route, $parameters, $referenceType)
            ->willReturn($expectedUrl);

        $actualUrl = $this->urlGeneratorAdapter->generate($route, $parameters, $referenceType);

        $this->assertSame($expectedUrl, $actualUrl);
    }
}
