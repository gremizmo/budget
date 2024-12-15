<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserFirstnameInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'The first name must be at least {{ limit }} characters long.',
            maxMessage: 'The first name cannot be longer than {{ limit }} characters.'
        )]
        public string $firstname,
    ) {
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }
}
