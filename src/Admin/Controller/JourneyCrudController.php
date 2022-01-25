<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Adventure\Entity\Journey;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

final class JourneyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Journey::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(EntityFilter::new('user', 'Utilisateur'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Journal de bord')
            ->setEntityLabelInPlural('Journaux de bord')
            ->setDefaultSort(['updatedAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user', 'Utilisateur')
            ->setCrudController(UserCrudController::class);
        yield AssociationField::new('currentLevel', 'Niveau actuel')
            ->setCrudController(UserCrudController::class);
        yield AssociationField::new('checkpoints', 'Checkpoints')
            ->setCrudController(UserCrudController::class)
            ->setTemplatePath('admin/field/checkpoints.html.twig');
        yield DateTimeField::new('updatedAt', 'Dernière mise à jour');
    }
}
