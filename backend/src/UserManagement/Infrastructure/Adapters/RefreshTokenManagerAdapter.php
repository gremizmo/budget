<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Adapters;

use App\UserManagement\Domain\Ports\Outbound\RefreshTokenManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface as JWTRefreshTokenManagerInterface;

final readonly class RefreshTokenManagerAdapter implements RefreshTokenManagerInterface
{
    public function __construct(private JWTRefreshTokenManagerInterface $refreshTokenManager)
    {
    }

    #[\Override]
    public function get(string $refreshToken): RefreshTokenInterface|null
    {
        return $this->refreshTokenManager->get($refreshToken);
    }
}
