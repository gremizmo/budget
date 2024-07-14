<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Serializer;

use App\Domain\Envelope\Entity\Envelope;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class EnvelopeNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $context['ignored_attributes'] = ['parent'];

        return $this->normalizer->normalize($object, $format, $context);
    }

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
