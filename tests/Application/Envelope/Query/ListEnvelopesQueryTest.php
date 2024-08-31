<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Query;

use App\BudgetManagement\Application\Envelope\Dto\ListEnvelopesInput;
use App\BudgetManagement\Application\Envelope\Query\ListEnvelopesQuery;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $user = new User();
        $user->setId(1);
        $query = new ListEnvelopesQuery($user, new ListEnvelopesInput());

        $this->assertSame(1, $query->getUser()->getId());
    }
}
