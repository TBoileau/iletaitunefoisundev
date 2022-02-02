<?php

declare(strict_types=1);

namespace App\Admin\EasyAdmin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class YoutubeField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): YoutubeField
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->addCssClass('field-text')
            ->setDefaultColumns('col-md-6 col-xxl-5')
            ->setTemplatePath('admin/field/youtube.html.twig')
            ->setFormType(UrlType::class);
    }
}
