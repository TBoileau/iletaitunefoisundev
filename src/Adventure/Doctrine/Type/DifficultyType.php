<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Type;

use App\Adventure\Entity\Difficulty;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

final class DifficultyType extends IntegerType
{
    public const NAME = 'difficulty';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (!$value instanceof Difficulty) {
            return null;
        }

        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Difficulty
    {
        if (!is_int($value)) {
            return null;
        }

        return Difficulty::from($value);
    }
}
