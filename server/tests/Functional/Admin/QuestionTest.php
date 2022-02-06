<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\QuestionCrudController;
use App\Admin\Entity\Administrator;
use App\Content\Doctrine\Repository\QuestionRepository;
use App\Content\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class QuestionTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfQuestions(): void
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
                ->setController(QuestionCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateQuestion(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $crawler = $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(QuestionCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $token = $crawler->filter('#Question__token')->attr('value');
        $referer = $crawler->filter('input[name=referrer]')->attr('value');

        $client->request(
            'POST',
            $adminUrlGenerator
                ->setController(QuestionCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl(),
            [
                'ea' => [
                    'newForm' => [
                        'btn' => 'saveAndReturn',
                    ],
                ],
                'referer' => $referer,
                'Question' => [
                    '_token' => $token,
                    'label' => 'Question 0',
                    'content' => 'Content 0',
                    'quiz' => 126,
                    'answers' => [
                        [
                            'label' => 'Réponse 0',
                            'content' => 'Content 0',
                            'good' => true,
                        ],
                        [
                            'label' => 'Réponse 1',
                            'content' => 'Content 1',
                            'good' => false,
                        ],
                        [
                            'label' => 'Réponse 2',
                            'content' => 'Content 2',
                            'good' => false,
                        ],
                    ],
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var QuestionRepository<Question> $questionRepository */
        $questionRepository = $client->getContainer()->get(QuestionRepository::class);

        $question = $questionRepository->findOneBy(['label' => 'Question 0']);

        self::assertNotNull($question);
        self::assertSame('Content 0', $question->getContent());
        self::assertSame('Question 0', $question->getLabel());
        self::assertSame(126, $question->getQuiz()->getId());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfQuestion(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Question $question */
        $question = $entityManager->getRepository(Question::class)->findOneBy(['label' => 'Question 1']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(QuestionCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($question->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateQuestion(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var Question $question */
        $question = $entityManager->getRepository(Question::class)->findOneBy(['label' => 'Question 1']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $crawler = $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(QuestionCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $question->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $token = $crawler->filter('#Question__token')->attr('value');
        $referer = $crawler->filter('input[name=referrer]')->attr('value');

        $client->request(
            'POST',
            $adminUrlGenerator
                ->setController(QuestionCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $question->getId())
                ->generateUrl(),
            [
                'ea' => [
                    'newForm' => [
                        'btn' => 'saveAndReturn',
                    ],
                ],
                'referer' => $referer,
                'Question' => [
                    '_token' => $token,
                    'label' => 'Question 0',
                    'content' => 'Content 0',
                    'quiz' => 126,
                    'answers' => [
                        [
                            'label' => 'Réponse 0',
                            'content' => 'Content 0',
                            'good' => true,
                        ],
                        [
                            'label' => 'Réponse 2',
                            'content' => 'Content 2',
                            'good' => false,
                        ],
                    ],
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var QuestionRepository<Question> $questionRepository */
        $questionRepository = $client->getContainer()->get(QuestionRepository::class);

        $question = $questionRepository->findOneBy(['label' => 'Question 0']);

        self::assertNotNull($question);
        self::assertSame('Content 0', $question->getContent());
        self::assertSame('Question 0', $question->getLabel());
        self::assertCount(2, $question->getAnswers());
    }
}
