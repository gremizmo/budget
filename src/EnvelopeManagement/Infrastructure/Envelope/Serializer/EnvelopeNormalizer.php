<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Serializer;

use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class EnvelopeNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     *
     * @throws ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $context['ignored_attributes'] = ['parent', 'children'];
        $data = $this->normalizer->normalize($object, $format, $context);

        if (!\is_array($data)) {
            $data = (array) $data;
        }

        if (isset($data['user']) && \is_array($data['user'])) {
            unset(
                $data['user']['password'],
                $data['user']['email'],
                $data['user']['firstname'],
                $data['user']['lastname'],
                $data['user']['consentGiven'],
                $data['user']['consentDate'],
                $data['user']['roles'],
                $data['user']['createdAt'],
                $data['user']['updatedAt'],
                $data['user']['userIdentifier'],
            );
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Envelope;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Envelope::class => true,
        ];
    }
}
