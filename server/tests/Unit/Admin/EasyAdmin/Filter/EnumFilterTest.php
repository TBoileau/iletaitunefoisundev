<?php

declare(strict_types=1);

namespace App\Tests\Unit\Admin\EasyAdmin\Filter;

use App\Admin\EasyAdmin\Filter\EnumFilter;
use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Type;
use App\Content\Entity\Format;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDto;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;

final class EnumFilterTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider provideEnum
     */
    public function shouldEditQueryBuilder(mixed $enum): void
    {
        $entityManager = self::createMock(EntityManagerInterface::class);

        $queryBuilder = new QueryBuilder($entityManager);

        $filter = EnumFilter::new('test');

        $filterDto = new FilterDto();
        $filterDto->setProperty('property');

        $filterDataDto = FilterDataDto::new(
            1,
            $filterDto,
            'a',
            ['comparison' => '=', 'value' => $enum]
        );

        $classMetadata = self::createMock(ClassMetadata::class);
        $classMetadata->method('getIdentifierFieldNames')->willReturn(['id']);

        $entityDto = new EntityDto(stdClass::class, $classMetadata);

        $filter->apply($queryBuilder, $filterDataDto, null, $entityDto);

        $where = $queryBuilder->getDQLPart('where');

        self::assertInstanceOf(Andx::class, $where);
        self::assertSame('a.property = :property_1', $where->getParts()[0]);
    }

    /**
     * @return Generator<string, array<array-key, mixed>>
     */
    public function provideEnum(): Generator
    {
        yield 'difficulty' => [Difficulty::Easy];
        yield 'type' => [Type::Main];
        yield 'format' => [Format::Unique];
    }
}
