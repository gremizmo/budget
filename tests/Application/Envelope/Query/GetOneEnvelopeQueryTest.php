<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Query;

use App\BudgetManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class GetOneEnvelopeQueryTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $envelopeId = 1;
        $query = new ShowEnvelopeQuery($envelopeId, new User());

        $this->assertSame($envelopeId, $query->getEnvelopeId());
    }
}
