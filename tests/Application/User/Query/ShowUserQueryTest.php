<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Query;

use App\UserManagement\Application\User\Query\ShowUserQuery;
use PHPUnit\Framework\TestCase;

class ShowUserQueryTest extends TestCase
{
    public function testQueryInstantiation(): void
    {
        $userEmail = 'test@example.com';
        $query = new ShowUserQuery($userEmail);

        $this->assertInstanceOf(ShowUserQuery::class, $query);
    }

    public function testGetUserEmail(): void
    {
        $userEmail = 'test@example.com';
        $query = new ShowUserQuery($userEmail);

        $this->assertSame($userEmail, $query->getUserEmail());
    }
}
