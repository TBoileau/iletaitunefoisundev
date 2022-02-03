<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\EasyAdmin\Field\DifficultyField;
use App\Admin\EasyAdmin\Filter\DifficultyFilter;
use App\Adventure\Entity\Quest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

final class QuestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Quest::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Nom'))
            ->add(DifficultyFilter::new('difficulty', 'Difficulté'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Quête')
            ->setEntityLabelInPlural('Quêtes')
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
        yield DifficultyField::new('difficulty', 'Difficulté');
        yield AssociationField::new('course', 'Cours')
            ->setCrudController(CourseCrudController::class);
        yield AssociationField::new('region', 'Région')
            ->setCrudController(RegionCrudController::class);
        yield AssociationField::new('relatives', 'Quêtes connexes')
            ->setTemplatePath('admin/field/quests.html.twig')
            ->hideOnForm();
    }
}
