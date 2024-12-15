<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DebitEnvelopeInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        #[Assert\Length(
            min: 1,
            max: 13,
            minMessage: 'The debit money must be at least 1 character long.',
            maxMessage: 'The debit money must be at most 13 characters long.'
        )]
        #[Assert\Regex(
            pattern: '/^\d+(\.\d{2})?$/',
            message: 'The debit money must be a string representing a number with up to two decimal places (e.g., "0.00").'
        )]
        public string $debitMoney,
    ) {
    }

    public function getDebitMoney(): string
    {
        return $this->debitMoney;
    }
}
