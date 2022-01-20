<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Domain\Adventure\Entity\Level;
use App\Domain\Adventure\Entity\Map;
use App\Domain\Course\Entity\Course;
use App\Domain\Node\Entity\Node;
use App\Domain\Security\Entity\User;
use App\Infrastructure\Repository\CourseRepository;
use App\Infrastructure\Repository\LevelRepository;
use App\Infrastructure\Repository\MapRepository;
use App\Infrastructure\Repository\NodeRepository;
use App\Infrastructure\Repository\UserRepository;
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
    }
}
