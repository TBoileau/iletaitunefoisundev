<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IncentiveFactory\Domain\Path\Level;

final class PathLevelType extends AbstractEnumType
{
    public const NAME = 'path_level';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return Level::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'int(1)';
    }
}
