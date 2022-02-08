<?php

declare(strict_types=1);

namespace App\Admin\EasyAdmin\Field;

use App\Content\Entity\Format;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

final class FormatField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): FormatField
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->addCssClass('field-select')
            ->setDefaultColumns('col-md-6 col-xxl-5')
            ->setTemplatePath('admin/field/format.html.twig')
            ->setFormType(EnumType::class)
            ->setFormTypeOption('class', Format::class);
    }
}
