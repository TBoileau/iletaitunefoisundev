<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\QuizCrudController;
use App\Admin\Entity\Administrator;
use App\Content\Doctrine\Repository\QuizRepository;
use App\Content\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class QuizTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfQuizs(): void
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
                ->setController(QuizCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateQuiz(): void
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
                ->setController(QuizCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'Quiz[title]' => 'Quiz 251',
            'Quiz[slug]' => 'quiz-251',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var QuizRepository<Quiz> $quizRepository */
        $quizRepository = $client->getContainer()->get(QuizRepository::class);

        $quiz = $quizRepository->findOneBy(['slug' => 'quiz-251']);

        self::assertNotNull($quiz);
        self::assertSame('quiz-251', $quiz->getSlug());
        self::assertSame('Quiz 251', $quiz->getTitle());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfQuiz(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Quiz $quiz */
        $quiz = $entityManager->getRepository(Quiz::class)->findOneBy(['slug' => 'quiz-1']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(QuizCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($quiz->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateQuiz(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var Quiz $quiz */
        $quiz = $entityManager->getRepository(Quiz::class)->findOneBy(['slug' => 'quiz-1']);

        /** @var Quiz $sibling */
        $sibling = $entityManager->getRepository(Quiz::class)->findOneBy(['slug' => 'quiz-4']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(QuizCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $quiz->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Sauvegarder les modifications', [
            'Quiz[title]' => 'Quiz 0',
            'Quiz[slug]' => 'quiz-0',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var QuizRepository<Quiz> $quizRepository */
        $quizRepository = $client->getContainer()->get(QuizRepository::class);

        $quiz = $quizRepository->findOneBy(['slug' => 'quiz-0']);

        self::assertNotNull($quiz);
        self::assertSame('quiz-0', $quiz->getSlug());
        self::assertSame('Quiz 0', $quiz->getTitle());
    }
}
