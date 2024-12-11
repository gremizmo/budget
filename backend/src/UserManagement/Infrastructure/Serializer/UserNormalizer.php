<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Serializer;

use App\UserManagement\Infrastructure\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class UserNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @param array<int, string[]> $context
     *
     * @return array<string, mixed>
     *
     * @throws ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $context['ignored_attributes'] = ['envelopes', 'passwordResetToken', 'passwordResetTokenExpiry'];

        $data = $this->normalizer->normalize($object, $format, $context);
        unset(
            $data['password'],
            $data['consentGiven'],
            $data['consentDate'],
            $data['roles'],
            $data['createdAt'],
            $data['updatedAt'],
            $data['userIdentifier'],
        );

        return $data;
    }

    /**
     * @param array<int, string[]> $context
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @return true[]
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }
}
