<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Type;

use App\Adventure\Entity\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

final class QuestTypeType extends IntegerType
{
    public const NAME = 'type';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (!$value instanceof Type) {
            return null;
        }

        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Type
    {
        if (!is_int($value)) {
            return null;
        }

        return Type::from($value);
    }
}
