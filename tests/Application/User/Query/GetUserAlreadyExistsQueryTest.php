<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Query;

use App\Application\User\Query\GetUserAlreadyExistsQuery;
use PHPUnit\Framework\TestCase;

class GetUserAlreadyExistsQueryTest extends TestCase
{
    public function testQueryInstantiation(): void
    {
        $userEmail = 'test@example.com';
        $query = new GetUserAlreadyExistsQuery($userEmail);

        $this->assertInstanceOf(GetUserAlreadyExistsQuery::class, $query);
    }

    public function testGetUserEmail(): void
    {
        $userEmail = 'test@example.com';
        $query = new GetUserAlreadyExistsQuery($userEmail);

        $this->assertSame($userEmail, $query->getUserEmail());
    }
}
