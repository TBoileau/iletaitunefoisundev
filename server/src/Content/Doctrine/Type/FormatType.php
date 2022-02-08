<?php

declare(strict_types=1);

namespace App\Content\Doctrine\Type;

use App\Content\Entity\Format;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class FormatType extends StringType
{
    public const NAME = 'format';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value instanceof Format) {
            return null;
        }

        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Format
    {
        if (!is_string($value)) {
            return null;
        }

        return Format::from($value);
    }
}
