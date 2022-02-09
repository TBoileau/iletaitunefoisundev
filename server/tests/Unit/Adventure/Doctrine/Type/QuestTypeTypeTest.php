<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Doctrine\Type;

use App\Adventure\Doctrine\Type\QuestTypeType;
use App\Adventure\Entity\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

final class QuestTypeTypeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnName(): void
    {
        $questTypeType = new QuestTypeType();

        self::assertEquals('type', $questTypeType->getName());
    }

    /**
     * @test
     */
    public function shouldConvertToDatabaseString(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $questTypeType = new QuestTypeType();

        self::assertEquals(
            1,
            $questTypeType->convertToDatabaseValue(Type::Main, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToDatabaseNull(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $questTypeType = new QuestTypeType();

        self::assertEquals(
            null,
            $questTypeType->convertToDatabaseValue(null, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToPhpFormat(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $questTypeType = new QuestTypeType();

        self::assertEquals(
            Type::Main,
            $questTypeType->convertToPHPValue(1, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToPhpNull(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $questTypeType = new QuestTypeType();

        self::assertEquals(
            null,
            $questTypeType->convertToPHPValue(null, $platform)
        );
    }
}
