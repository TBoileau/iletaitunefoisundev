<?php

declare(strict_types=1);

namespace App\Content\Serializer;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Content\UseCase\SubmitResponse\SubmitResponseInput;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

final class ResponseDenormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private IriConverterInterface $iriConverter)
    {
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return SubmitResponseInput::class === $type;
    }

    /**
     * @param array{answers: array<array-key, string>} $data
     *
     * @phpstan-ignore-next-line
     */
    public function denormalize(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ): SubmitResponseInput {
        $submitResponseInput = new SubmitResponseInput();
        /* @phpstan-ignore-next-line */
        $submitResponseInput->answers = array_map(
            fn (string $iri): object => $this->iriConverter->getItemFromIri($iri),
            $data['answers']
        );

        return $submitResponseInput;
    }
}
