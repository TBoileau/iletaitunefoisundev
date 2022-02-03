<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Entity\Administrator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

final class AdministratorCrudController extends AbstractUserCrudController
{
    public static function getEntityFqcn(): string
    {
        return Administrator::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Administrateur')
            ->setEntityLabelInPlural('Administrateurs');
    }
}
