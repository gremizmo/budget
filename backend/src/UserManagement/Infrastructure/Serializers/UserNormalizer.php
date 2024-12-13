<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Serializers;

use App\UserManagement\ReadModels\Views\UserView;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class UserNormalizer implements NormalizerInterface
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
        return $data instanceof UserView;
    }

    /**
     * @return true[]
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            UserView::class => true,
        ];
    }
}
