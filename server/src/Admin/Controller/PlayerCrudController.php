<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Contract\Gateway\UserGateway;
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

final class PlayerCrudController extends AbstractCrudController
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Player::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Nom'))
            ->add(EntityFilter::new('user', 'Utilisateur'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Joueur')
            ->setEntityLabelInPlural('Joueurs')
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
        yield TextField::new('name', 'Nom')
            ->setFormTypeOption('empty_data', '');
        yield AssociationField::new('user', 'Utilisateur')
            ->setCrudController(UserCrudController::class)
            ->setFormTypeOption(
                'query_builder',
                static fn (UserGateway $userGateway) => $userGateway
                    ->createQueryBuilderUsersWhoHaveNotCreatedTheirPlayer()
            )
            ->hideWhenUpdating();
        yield AssociationField::new('journey', 'Journal de bord')
            ->setCrudController(JourneyCrudController::class)
            ->hideOnForm();
        yield AssociationField::new('save', 'Sauvegarde')
            ->setCrudController(SaveCrudController::class)
            ->hideOnForm();
    }

    public function createEntity(string $entityFqcn): Player
    {
        $player = new Player();
        $player->setId($this->ulidGenerator->generate());

        $journey = new Journey();
        $journey->setId($this->ulidGenerator->generate());
        $player->setJourney($journey);

        return $player;
    }
}
