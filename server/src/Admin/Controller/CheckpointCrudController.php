<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\EasyAdmin\Field\DifficultyField;
use App\Adventure\Entity\Checkpoint;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

final class CheckpointCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Checkpoint::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('finishedAt', 'Date de passage'))
            ->add(EntityFilter::new('journey', 'Journal de bord'))
            ->add(EntityFilter::new('quest', 'Quête'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Checkpoint')
            ->setEntityLabelInPlural('Checkpoints')
            ->setDefaultSort(['startedAt' => 'DESC', 'finishedAt' => 'DESC']);
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
        yield AssociationField::new('quest', 'Quête')
            ->setCrudController(QuestCrudController::class);
        yield DifficultyField::new('quest.difficulty', 'Difficulté');
        yield DateTimeField::new('startedAt', 'Commencée le');
        yield DateTimeField::new('finishedAt', 'Terminée le');
        yield AssociationField::new('journey.player', 'Joueur')
            ->setCrudController(PlayerCrudController::class);
        yield AssociationField::new('session', 'Session de Quiz')
            ->setCrudController(SessionCrudController::class);
        yield AssociationField::new('journey', 'Journal de bord')
            ->setCrudController(JourneyCrudController::class);
    }
}
