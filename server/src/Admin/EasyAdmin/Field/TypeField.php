<?php

declare(strict_types=1);

namespace App\Admin\EasyAdmin\Field;

use App\Adventure\Entity\Type;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

final class TypeField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): TypeField
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->addCssClass('field-select')
            ->setDefaultColumns('col-md-6 col-xxl-5')
            ->setTemplatePath('admin/field/type.html.twig')
            ->setFormType(EnumType::class)
            ->setFormTypeOption('class', Type::class);
    }
}
