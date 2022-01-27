<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Adventure\Entity\Save;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

final class SaveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Save::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('player', 'Joueur'))
            ->add(EntityFilter::new('world', 'World'))
            ->add(EntityFilter::new('continent', 'Continent'))
            ->add(EntityFilter::new('region', 'Région'))
            ->add(EntityFilter::new('quest', 'Quête'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sauvegarde')
            ->setEntityLabelInPlural('Sauvegardes');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('player', 'Joueur')
            ->setCrudController(PlayerCrudController::class);
        yield AssociationField::new('world', 'Monde')
            ->setCrudController(WorldCrudController::class);
        yield AssociationField::new('continent', 'Continent')
            ->setCrudController(ContinentCrudController::class);
        yield AssociationField::new('region', 'Région')
            ->setCrudController(RegionCrudController::class);
        yield AssociationField::new('quest', 'Quête')
            ->setCrudController(QuestCrudController::class);
        yield AssociationField::new('checkpoint', 'Checkpoint')
            ->setCrudController(CheckpointCrudController::class);
    }
}
