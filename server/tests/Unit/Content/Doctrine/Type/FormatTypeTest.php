<?php

declare(strict_types=1);

namespace App\Tests\Unit\Content\Doctrine\Type;

use App\Content\Doctrine\Type\FormatType;
use App\Content\Entity\Format;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

final class FormatTypeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnName(): void
    {
        $formatType = new FormatType();

        self::assertEquals('format', $formatType->getName());
    }

    /**
     * @test
     */
    public function shouldConvertToDatabaseString(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $formatType = new FormatType();

        self::assertEquals(
            'unique',
            $formatType->convertToDatabaseValue(Format::Unique, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToDatabaseNull(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $formatType = new FormatType();

        self::assertEquals(
            null,
            $formatType->convertToDatabaseValue(null, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToPhpFormat(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $formatType = new FormatType();

        self::assertEquals(
            Format::Unique,
            $formatType->convertToPHPValue('unique', $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToPhpNull(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $formatType = new FormatType();

        self::assertEquals(
            null,
            $formatType->convertToPHPValue(null, $platform)
        );
    }
}
