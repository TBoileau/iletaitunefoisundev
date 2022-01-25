<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Security\Entity\AbstractUser;
use App\Security\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

final class UserCrudController extends AbstractUserCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs');
    }

    public function createUser(): AbstractUser
    {
        return new User();
    }

    public function configureFields(string $pageName): iterable
    {
        yield from parent::configureFields($pageName);

        yield AssociationField::new('history', 'Historique')
            ->setTemplatePath('admin/field/history.html.twig')
            ->hideOnForm();
    }
}
