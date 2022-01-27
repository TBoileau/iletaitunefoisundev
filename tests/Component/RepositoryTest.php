<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Admin\Entity\Administrator;
use App\Admin\Repository\AdministratorRepository;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\Save;
use App\Adventure\Entity\World;
use App\Adventure\Repository\CheckpointRepository;
use App\Adventure\Repository\ContinentRepository;
use App\Adventure\Repository\JourneyRepository;
use App\Adventure\Repository\PlayerRepository;
use App\Adventure\Repository\QuestRepository;
use App\Adventure\Repository\RegionRepository;
use App\Adventure\Repository\SaveRepository;
use App\Adventure\Repository\WorldRepository;
use App\Content\Entity\Course;
use App\Content\Entity\Node;
use App\Content\Repository\CourseRepository;
use App\Content\Repository\NodeRepository;
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
        yield 'save entity' => [Save::class, SaveRepository::class]; /* @phpstan-ignore-line */
    }
}
