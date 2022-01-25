<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Level;
use App\Adventure\Entity\Map;
use App\Adventure\Repository\CheckpointRepository;
use App\Adventure\Repository\JourneyRepository;
use App\Adventure\Repository\LevelRepository;
use App\Adventure\Repository\MapRepository;
use App\Node\Entity\Course;
use App\Node\Entity\Node;
use App\Node\Repository\CourseRepository;
use App\Node\Repository\NodeRepository;
use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
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
        yield 'node entity' => [Node::class, NodeRepository::class]; /* @phpstan-ignore-line */
        yield 'course entity' => [Course::class, CourseRepository::class]; /* @phpstan-ignore-line */
        yield 'map entity' => [Map::class, MapRepository::class]; /* @phpstan-ignore-line */
        yield 'level entity' => [Level::class, LevelRepository::class]; /* @phpstan-ignore-line */
        yield 'journey entity' => [Journey::class, JourneyRepository::class]; /* @phpstan-ignore-line */
        yield 'checkpoint entity' => [Checkpoint::class, CheckpointRepository::class]; /* @phpstan-ignore-line */
    }
}
