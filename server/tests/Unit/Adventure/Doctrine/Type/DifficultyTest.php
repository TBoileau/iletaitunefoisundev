<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Doctrine\Type;

use App\Adventure\Doctrine\Type\DifficultyType;
use App\Adventure\Entity\Difficulty;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

final class DifficultyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnName(): void
    {
        $difficultyType = new DifficultyType();

        self::assertEquals('difficulty', $difficultyType->getName());
    }

    /**
     * @test
     */
    public function shouldConvertToDatabaseString(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $difficultyType = new DifficultyType();

        self::assertEquals(
            1,
            $difficultyType->convertToDatabaseValue(Difficulty::Easy, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToDatabaseNull(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $difficultyType = new DifficultyType();

        self::assertEquals(
            null,
            $difficultyType->convertToDatabaseValue(null, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToPhpFormat(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $difficultyType = new DifficultyType();

        self::assertEquals(
            Difficulty::Easy,
            $difficultyType->convertToPHPValue(1, $platform)
        );
    }

    /**
     * @test
     */
    public function shouldConvertToPhpNull(): void
    {
        $platform = self::createMock(AbstractPlatform::class);

        $difficultyType = new DifficultyType();

        self::assertEquals(
            null,
            $difficultyType->convertToPHPValue(null, $platform)
        );
    }
}
