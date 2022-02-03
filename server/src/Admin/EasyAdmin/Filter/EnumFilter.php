<?php

declare(strict_types=1);

namespace App\Admin\EasyAdmin\Filter;

use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Type;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

final class EnumFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName, string $label = null): EnumFilter
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(EnumType::class)
            ->setFormTypeOption('class', Type::class)
            ->setFormTypeOption('translation_domain', 'EasyAdminBundle');
    }

    public function apply(
        QueryBuilder $queryBuilder,
        FilterDataDto $filterDataDto,
        ?FieldDto $fieldDto,
        EntityDto $entityDto
    ): void {
        $alias = $filterDataDto->getEntityAlias();
        $property = $filterDataDto->getProperty();
        $comparison = $filterDataDto->getComparison();
        $parameterName = $filterDataDto->getParameterName();

        /** @var Difficulty $value */
        $value = $filterDataDto->getValue();

        $queryBuilder
            ->andWhere(sprintf('%s.%s %s :%s', $alias, $property, $comparison, $parameterName))
            ->setParameter($parameterName, $value->value);
    }
}
