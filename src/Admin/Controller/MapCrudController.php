<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Adventure\Entity\Map;
use App\Core\Uid\UlidGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

final class MapCrudController extends AbstractCrudController
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Map::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Nom'))
            ->add(EntityFilter::new('previous', 'Carte précédente'))
            ->add(EntityFilter::new('next', 'Carte suivante'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Carte')
            ->setEntityLabelInPlural('Cartes')
            ->setDefaultSort(['name' => 'ASC']);
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
        yield TextField::new('name', 'Nom');
        yield AssociationField::new('start', 'Premier niveau')
            ->setCrudController(LevelCrudController::class)
            ->hideWhenCreating();
        yield AssociationField::new('previous', 'Carte précédente')
            ->setCrudController(MapCrudController::class);
        yield AssociationField::new('next', 'Carte suivante')
            ->setCrudController(MapCrudController::class)
            ->hideOnForm();
    }

    public function createEntity(string $entityFqcn): Map
    {
        $map = new Map();
        $map->setId($this->ulidGenerator->generate());

        return $map;
    }
}
