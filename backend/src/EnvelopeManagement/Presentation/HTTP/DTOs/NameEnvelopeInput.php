<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class NameEnvelopeInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 1,
            max: 50,
            minMessage: 'The name must be at least 1 character long.',
            maxMessage: 'The name must be at most 50 characters long.'
        )]
        #[Assert\Regex(
            pattern: '/^[\p{L}\p{N} ]+$/u',
            message: 'The name can only contain letters (including letters with accents), numbers (0-9), and spaces. No special characters are allowed.'
        )]
        public string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
