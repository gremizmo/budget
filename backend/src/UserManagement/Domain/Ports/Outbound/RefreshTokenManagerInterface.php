<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Ports\Outbound;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;

interface RefreshTokenManagerInterface
{
    public function get(string $refreshToken): RefreshTokenInterface|null;
}
