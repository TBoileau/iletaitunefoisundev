<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Adventure\Entity\Level;
use App\Core\Uid\UlidGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

final class LevelCrudController extends AbstractCrudController
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Level::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('course', 'Cours'))
            ->add(EntityFilter::new('map', 'Carte'))
            ->add(EntityFilter::new('previous', 'Niveau précédent'))
            ->add(EntityFilter::new('next', 'Niveau suivant'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Niveau')
            ->setEntityLabelInPlural('Niveaux')
            ->setDefaultSort(['map' => 'ASC', 'order' => 'ASC']);
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
        yield IntegerField::new('order', 'Ordre');
        yield AssociationField::new('course', 'Cours')
            ->setCrudController(CourseCrudController::class);
        yield AssociationField::new('map', 'Carte')
            ->setCrudController(MapCrudController::class);
        yield AssociationField::new('previous', 'Niveau précédent')
            ->setCrudController(LevelCrudController::class);
        yield AssociationField::new('next', 'Niveau suivant')
            ->setCrudController(LevelCrudController::class)
            ->hideOnForm();
    }

    public function createEntity(string $entityFqcn): Level
    {
        $level = new Level();
        $level->setId($this->ulidGenerator->generate());

        return $level;
    }
}
