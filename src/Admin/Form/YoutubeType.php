<?php

declare(strict_types=1);

namespace App\Admin\Form;

use RuntimeException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Url;

/**
 * @template-implements DataTransformerInterface<int, string>
 */
final class YoutubeType extends TextType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function transform(mixed $value): string /* @phpstan-ignore-line */
    {
        if (!is_string($value)) {
            return '';
        }

        return sprintf('https://www.youtube.com/watch?v=%s', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(mixed $value): string /* @phpstan-ignore-line */
    {
        if (!is_string($value)) {
            throw new RuntimeException('The value is not a string.');
        }

        if (0 === preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=(.+)$/', $value, $matches)) {
            throw new TransformationFailedException(sprintf('The url "%s" does not match with youtube video url !', $value));
        }

        return $matches[1];
    }
}
