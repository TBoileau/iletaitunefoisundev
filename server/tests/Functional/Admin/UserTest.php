<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\UserCrudController;
use App\Admin\Entity\Administrator;
use App\Security\Doctrine\Repository\UserRepository;
use App\Security\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfUsers(): void
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
                ->setController(UserCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldShowDetailOfUser(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'user+1@email.com']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($user->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateUser(): void
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
                ->setController(UserCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'User[email]' => 'user+6@email.com',
            'User[password]' => 'Password123!',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var UserRepository<User> $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'user+6@email.com']);

        /** @var UserPasswordHasherInterface $userPasswordHasher */
        $userPasswordHasher = $client->getContainer()->get(UserPasswordHasherInterface::class);

        self::assertNotNull($user);
        self::assertSame('user+6@email.com', $user->getEmail());
        self::assertTrue($userPasswordHasher->isPasswordValid($user, 'Password123!'));
    }

    /**
     * @test
     */
    public function shouldUpdateUser(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'user+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $user->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Sauvegarder les modifications', [
            'User[email]' => 'user+6@email.com',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var UserRepository<User> $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'user+6@email.com']);

        self::assertNotNull($user);
        self::assertSame('user+6@email.com', $user->getEmail());
    }
}
