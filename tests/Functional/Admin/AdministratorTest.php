<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\AdministratorCrudController;
use App\Security\Entity\Administrator;
use App\Security\Entity\User;
use App\Security\Repository\AdministratorRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

final class AdministratorTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfAdministrators(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(AdministratorCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldShowDetailOfAdministrator(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(AdministratorCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($admin->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateAdministrator(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(AdministratorCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'Administrator[email]' => 'admin+6@email.com',
            'Administrator[password]' => 'Password123!',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var AdministratorRepository<User> $adminRepository */
        $adminRepository = $client->getContainer()->get(AdministratorRepository::class);

        $admin = $adminRepository->findOneBy(['email' => 'admin+6@email.com']);

        /** @var UserPasswordHasherInterface $adminPasswordHasher */
        $adminPasswordHasher = $client->getContainer()->get(UserPasswordHasherInterface::class);

        self::assertNotNull($admin);
        self::assertSame('admin+6@email.com', $admin->getEmail());
        self::assertTrue($adminPasswordHasher->isPasswordValid($admin, 'Password123!'));
        self::assertTrue(Ulid::isValid((string) $admin->getId()));
    }

    /**
     * @test
     */
    public function shouldUpdateAdministrator(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var User $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(AdministratorCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $admin->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Sauvegarder les modifications', [
            'Administrator[email]' => 'admin+6@email.com',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var AdministratorRepository<User> $adminRepository */
        $adminRepository = $client->getContainer()->get(AdministratorRepository::class);

        $admin = $adminRepository->findOneBy(['email' => 'admin+6@email.com']);

        self::assertNotNull($admin);
        self::assertSame('admin+6@email.com', $admin->getEmail());
    }
}
