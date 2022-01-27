<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Adventure\Entity\Region;
use App\Core\Uid\UlidGeneratorInterface;
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
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

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
        yield AssociationField::new('quests', 'Quêtes')
            ->setTemplatePath('admin/field/quests.html.twig')
            ->hideOnForm();
    }

    public function createEntity(string $entityFqcn): Region
    {
        $region = new Region();
        $region->setId($this->ulidGenerator->generate());

        return $region;
    }
}
