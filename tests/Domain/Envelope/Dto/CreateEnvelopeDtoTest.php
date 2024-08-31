<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Dto;

use App\BudgetManagement\Application\Envelope\Dto\CreateEnvelopeInput;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeDtoTest extends TestCase
{
    public function testGetTitle(): void
    {
        $title = 'Test Title';
        $createEnvelopeDto = new CreateEnvelopeInput($title, '1000.00', '2000.00');

        $this->assertSame($title, $createEnvelopeDto->getTitle());
    }

    public function testGetCurrentBudget(): void
    {
        $currentBudget = '1000.00';
        $createEnvelopeDto = new CreateEnvelopeInput('Test Title', $currentBudget, '2000.00');

        $this->assertSame($currentBudget, $createEnvelopeDto->getCurrentBudget());
    }

    public function testGetTargetBudget(): void
    {
        $targetBudget = '2000.00';
        $createEnvelopeDto = new CreateEnvelopeInput('Test Title', '1000.00', $targetBudget);

        $this->assertSame($targetBudget, $createEnvelopeDto->getTargetBudget());
    }

    public function testGetParentId(): void
    {
        $parentId = 1;
        $createEnvelopeDto = new CreateEnvelopeInput('Test Title', '1000.00', '2000.00', $parentId);

        $this->assertSame($parentId, $createEnvelopeDto->getParentId());
    }

    public function testGetParentIdWithNull(): void
    {
        $createEnvelopeDto = new CreateEnvelopeInput('Test Title', '1000.00', '2000.00');

        $this->assertNull($createEnvelopeDto->getParentId());
    }
}
