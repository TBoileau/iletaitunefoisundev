<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\PlayerCrudController;
use App\Admin\Entity\Administrator;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Region;
use App\Adventure\Repository\PlayerRepository;
use App\Content\Entity\Course;
use App\Security\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class PlayerTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfPlayers(): void
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
                ->setController(PlayerCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreatePlayer(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $user = new User();
        $user->setId(new Ulid());
        $user->setPassword('password');
        $user->setEmail('user+6@email.com');
        $entityManager->persist($user);
        $entityManager->flush();

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(PlayerCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'Player[name]' => 'Player 6',
            'Player[user]' => $user->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var PlayerRepository<Player> $playerRepository */
        $playerRepository = $client->getContainer()->get(PlayerRepository::class);

        $player = $playerRepository->findOneBy(['name' => 'Player 6']);

        self::assertNotNull($player);
        self::assertSame('Player 6', $player->getName());
        self::assertEquals($user->getId(), $player->getUser()->getId());
        self::assertTrue(Ulid::isValid((string) $player->getId()));
    }

    /**
     * @test
     */
    public function shouldShowDetailOfPlayer(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Player $player */
        $player = $entityManager->getRepository(Player::class)->findOneBy(['name' => 'Player 1']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(PlayerCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($player->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdatePlayer(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var Player $player */
        $player = $entityManager->getRepository(Player::class)->findOneBy(['name' => 'Player 1']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(PlayerCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $player->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Region $region */
        $region = $entityManager->getRepository(Region::class)->findOneBy(['name' => 'Region 2']);

        /** @var Course $course */
        $course = $entityManager->getRepository(Course::class)->findOneBy([]);

        $client->submitForm('Sauvegarder les modifications', [
            'Player[name]' => 'Player 0',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var PlayerRepository<Player> $playerRepository */
        $playerRepository = $client->getContainer()->get(PlayerRepository::class);

        $player = $playerRepository->findOneBy(['name' => 'Player 0']);

        self::assertNotNull($player);
        self::assertSame('Player 0', $player->getName());
    }
}
