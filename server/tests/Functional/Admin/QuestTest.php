<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\QuestCrudController;
use App\Admin\Entity\Administrator;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\Type;
use App\Content\Entity\Course;
use App\Content\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class QuestTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfQuests(): void
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
                ->setController(QuestCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateQuest(): void
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
                ->setController(QuestCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Region $region */
        $region = $entityManager->getRepository(Region::class)->findOneBy(['name' => 'Region 1']);

        /** @var Course $course */
        $course = $entityManager->getRepository(Course::class)->findOneBy([]);

        /** @var Quiz $quiz */
        $quiz = $entityManager->getRepository(Quiz::class)->findOneBy([]);

        $client->submitForm('Créer', [
            'Quest[name]' => 'Quest 6',
            'Quest[region]' => $region->getId(),
            'Quest[course]' => $course->getId(),
            'Quest[quiz]' => $quiz->getId(),
            'Quest[difficulty]' => Difficulty::Easy->value,
            'Quest[type]' => Type::Main->value,
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = $client->getContainer()->get(QuestRepository::class);

        $quest = $questRepository->findOneBy(['name' => 'Quest 6']);

        self::assertNotNull($quest);
        self::assertSame('Quest 6', $quest->getName());
        self::assertEquals($region->getId(), $quest->getRegion()->getId());
        self::assertEquals($course->getId(), $quest->getCourse()->getId());
        self::assertEquals(Difficulty::Easy, $quest->getDifficulty());
        self::assertNotNull($quest->getQuiz());
        self::assertEquals($quiz->getId(), $quest->getQuiz()->getId());
        self::assertEquals(Type::Main, $quest->getType());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfQuest(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Quest $quest */
        $quest = $entityManager->getRepository(Quest::class)->findOneBy(['name' => 'Quest 1']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(QuestCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($quest->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateQuest(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var Quest $quest */
        $quest = $entityManager->getRepository(Quest::class)->findOneBy(['name' => 'Quest 1']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(QuestCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $quest->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Region $region */
        $region = $entityManager->getRepository(Region::class)->findOneBy(['name' => 'Region 2']);

        /** @var Course $course */
        $course = $entityManager->getRepository(Course::class)->findOneBy([]);

        /** @var Quiz $quiz */
        $quiz = $entityManager->getRepository(Quiz::class)->findOneBy([]);

        $client->submitForm('Sauvegarder les modifications', [
            'Quest[name]' => 'Quest 0',
            'Quest[region]' => $region->getId(),
            'Quest[course]' => $course->getId(),
            'Quest[quiz]' => $quiz->getId(),
            'Quest[difficulty]' => Difficulty::Easy->value,
            'Quest[type]' => Type::Main->value,
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = $client->getContainer()->get(QuestRepository::class);

        $quest = $questRepository->findOneBy(['name' => 'Quest 0']);

        self::assertNotNull($quest);
        self::assertSame('Quest 0', $quest->getName());
        self::assertEquals($region->getId(), $quest->getRegion()->getId());
        self::assertEquals($course->getId(), $quest->getCourse()->getId());
        self::assertNotNull($quest->getQuiz());
        self::assertEquals($quiz->getId(), $quest->getQuiz()->getId());
        self::assertEquals(Difficulty::Easy, $quest->getDifficulty());
        self::assertEquals(Type::Main, $quest->getType());
    }
}
