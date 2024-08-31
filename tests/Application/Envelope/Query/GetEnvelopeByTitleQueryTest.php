<?php

namespace App\Tests\Application\Envelope\Query;

use App\BudgetManagement\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Domain\Shared\Model\UserInterface;
use PHPUnit\Framework\TestCase;

class GetEnvelopeByTitleQueryTest extends TestCase
{
    public function testGetTitle(): void
    {
        $title = 'Test Title';
        $user = $this->createMock(UserInterface::class);
        $query = new GetEnvelopeByTitleQuery($title, $user);

        $this->assertEquals($title, $query->getTitle());
    }

    public function testGetUser(): void
    {
        $title = 'Test Title';
        $user = $this->createMock(UserInterface::class);
        $query = new GetEnvelopeByTitleQuery($title, $user);

        $this->assertSame($user, $query->getUser());
    }
}
