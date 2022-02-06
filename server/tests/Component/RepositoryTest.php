<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Admin\Doctrine\Repository\AdministratorRepository;
use App\Admin\Entity\Administrator;
use App\Adventure\Doctrine\Repository\CheckpointRepository;
use App\Adventure\Doctrine\Repository\ContinentRepository;
use App\Adventure\Doctrine\Repository\JourneyRepository;
use App\Adventure\Doctrine\Repository\PlayerRepository;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\Doctrine\Repository\WorldRepository;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\World;
use App\Content\Doctrine\Repository\AnswerRepository;
use App\Content\Doctrine\Repository\CourseRepository;
use App\Content\Doctrine\Repository\NodeRepository;
use App\Content\Doctrine\Repository\PlayerQuizRepository;
use App\Content\Doctrine\Repository\QuestionRepository;
use App\Content\Doctrine\Repository\QuizRepository;
use App\Content\Doctrine\Repository\ResponseRepository;
use App\Content\Entity\Answer;
use App\Content\Entity\Course;
use App\Content\Entity\Node;
use App\Content\Entity\PlayerQuiz;
use App\Content\Entity\Question;
use App\Content\Entity\Quiz;
use App\Content\Entity\Response;
use App\Security\Doctrine\Repository\UserRepository;
use App\Security\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class RepositoryTest extends KernelTestCase
{
    /**
     * @test
     *
     * @param class-string $entityClass
     * @param class-string $repositoryClass
     *
     * @dataProvider provideEntities
     */
    public function shouldReturnRepositoryByEntity(string $entityClass, string $repositoryClass): void
    {
        self::bootKernel();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $repository = $entityManager->getRepository($entityClass);

        self::assertInstanceOf($repositoryClass, $repository);
    }

    /**
     * @return Generator<string, array{entityClass: class-string, repositoryClass: class-string}>
     */
    public function provideEntities(): Generator
    {
        yield 'user entity' => [User::class, UserRepository::class]; /* @phpstan-ignore-line */
        yield 'administrator entity' => [Administrator::class, AdministratorRepository::class]; /* @phpstan-ignore-line */
        yield 'node entity' => [Node::class, NodeRepository::class]; /* @phpstan-ignore-line */
        yield 'course entity' => [Course::class, CourseRepository::class]; /* @phpstan-ignore-line */
        yield 'world entity' => [World::class, WorldRepository::class]; /* @phpstan-ignore-line */
        yield 'continent entity' => [Continent::class, ContinentRepository::class]; /* @phpstan-ignore-line */
        yield 'region entity' => [Region::class, RegionRepository::class]; /* @phpstan-ignore-line */
        yield 'quest entity' => [Quest::class, QuestRepository::class]; /* @phpstan-ignore-line */
        yield 'player entity' => [Player::class, PlayerRepository::class]; /* @phpstan-ignore-line */
        yield 'journey entity' => [Journey::class, JourneyRepository::class]; /* @phpstan-ignore-line */
        yield 'checkpoint entity' => [Checkpoint::class, CheckpointRepository::class]; /* @phpstan-ignore-line */
        yield 'quiz entity' => [Quiz::class, QuizRepository::class]; /* @phpstan-ignore-line */
        yield 'question entity' => [Question::class, QuestionRepository::class]; /* @phpstan-ignore-line */
        yield 'answer entity' => [Answer::class, AnswerRepository::class]; /* @phpstan-ignore-line */
        yield 'response entity' => [Response::class, ResponseRepository::class]; /* @phpstan-ignore-line */
        yield 'player quiz entity' => [PlayerQuiz::class, PlayerQuizRepository::class]; /* @phpstan-ignore-line */
    }
}
