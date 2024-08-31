<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Dto;

use App\BudgetManagement\Application\Envelope\Dto\EditEnvelopeInput;
use PHPUnit\Framework\TestCase;

class UpdateEnvelopeDtoTest extends TestCase
{
    public function testGetTitle(): void
    {
        $title = 'Updated Title';
        $updateEnvelopeDto = new EditEnvelopeInput($title, '1000.00', '2000.00');

        $this->assertSame($title, $updateEnvelopeDto->getTitle());
    }

    public function testGetCurrentBudget(): void
    {
        $currentBudget = '1000.00';
        $updateEnvelopeDto = new EditEnvelopeInput('Updated Title', $currentBudget, '2000.00');

        $this->assertSame($currentBudget, $updateEnvelopeDto->getCurrentBudget());
    }

    public function testGetTargetBudget(): void
    {
        $targetBudget = '2000.00';
        $updateEnvelopeDto = new EditEnvelopeInput('Updated Title', '1000.00', $targetBudget);

        $this->assertSame($targetBudget, $updateEnvelopeDto->getTargetBudget());
    }

    public function testGetParentId(): void
    {
        $parentId = 1;
        $updateEnvelopeDto = new EditEnvelopeInput(
            'Updated Title',
            '1000.00',
            '2000.00',
            $parentId
        );

        $this->assertSame($parentId, $updateEnvelopeDto->getParentId());
    }

    public function testGetParentIdWithNull(): void
    {
        $updateEnvelopeDto = new EditEnvelopeInput('Updated Title', '1000.00', '2000.00');

        $this->assertNull($updateEnvelopeDto->getParentId());
    }
}
