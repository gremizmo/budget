<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Dto;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateEnvelopeDto implements CreateEnvelopeDtoInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        public string $currentBudget,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        public string $targetBudget,
        public ?int $parentId = null,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCurrentBudget(): string
    {
        return $this->currentBudget;
    }

    public function getTargetBudget(): string
    {
        return $this->targetBudget;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }
}
