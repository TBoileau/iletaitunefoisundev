<?php

declare(strict_types=1);

namespace App\UserInterface\Controller\Admin;

use App\Domain\Node\Entity\Course;
use App\Domain\Shared\Uuid\UlidGeneratorInterface;
use App\Infrastructure\EasyAdmin\Field\YoutubeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class CourseCrudController extends AbstractCrudController
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Cours')
            ->setEntityLabelInPlural('Cours')
            ->setDefaultSort(['title' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Titre');
        yield SlugField::new('slug', 'Slug')->setTargetFieldName('title');
        yield TextEditorField::new('description', 'Description');
        yield YoutubeField::new('youtubeId', 'Vidéo Youtube');
        yield AssociationField::new('siblings', 'Relations')
            ->setTemplatePath('admin/field/siblings.html.twig');
    }

    public function createEntity(string $entityFqcn): Course
    {
        $course = new Course();
        $course->setId($this->ulidGenerator->generate());

        return $course;
    }
}
