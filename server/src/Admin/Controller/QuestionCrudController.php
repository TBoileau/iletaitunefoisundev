<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Form\AnswerType;
use App\Content\Entity\Question;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

final class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('quiz', 'Quiz'))
            ->add(TextFilter::new('label', 'Intitulé'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Question')
            ->setEntityLabelInPlural('Question')
            ->setDefaultSort(['label' => 'ASC']);
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
        yield TextField::new('label', 'Intitulé')
            ->setFormTypeOption('empty_data', '');
        yield TextEditorField::new('content', 'Contenu')
            ->setRequired(false);
        yield AssociationField::new('quiz', 'Quiz')
            ->setCrudController(QuizCrudController::class);
        yield CollectionField::new('answers', 'Réponses')
            ->allowAdd(true)
            ->allowAdd(true)
            ->setTemplatePath('admin/field/answers.html.twig')
            ->setEntryType(AnswerType::class)
            ->setEntryIsComplex(true)
            ->setFormTypeOption('by_reference', false);
    }
}
