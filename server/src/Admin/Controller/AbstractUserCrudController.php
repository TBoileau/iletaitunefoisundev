<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Entity\AbstractUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractUserCrudController extends AbstractCrudController
{
    public function __construct(
        private UlidGeneratorInterface $ulidGenerator,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(TextFilter::new('email', 'Email'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['email' => 'ASC']);
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
        yield TextField::new('email', 'Email')
            ->setFormTypeOption('empty_data', '');
        yield TextField::new('password', 'Mot de passe')
            ->setFormTypeOption('empty_data', '')
            ->onlyWhenCreating();
    }

    abstract public function createUser(): AbstractUser;

    public function createEntity(string $entityFqcn): AbstractUser
    {
        $user = $this->createUser();
        $user->setId($this->ulidGenerator->generate());

        return $user;
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        if ($entityInstance instanceof AbstractUser && null !== $entityInstance->getPassword()) {
            $entityInstance->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $entityInstance,
                    $entityInstance->getPassword()
                )
            );
        }
        parent::persistEntity($entityManager, $entityInstance);
    }
}
