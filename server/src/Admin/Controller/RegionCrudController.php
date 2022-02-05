<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Adventure\Entity\Region;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

final class RegionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Region::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(TextFilter::new('name', 'Nom'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Région')
            ->setEntityLabelInPlural('Régions')
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
        yield AssociationField::new('continent', 'Continent')
            ->setCrudController(ContinentCrudController::class);
        yield AssociationField::new('firstQuest', 'Première quête')
            ->setRequired(false)
            ->setCrudController(QuestCrudController::class);
        yield AssociationField::new('quests', 'Quêtes')
            ->setTemplatePath('admin/field/quests.html.twig')
            ->hideOnForm();
    }
}
