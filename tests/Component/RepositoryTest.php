<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Domain\Security\Entity\User;
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
        /*
         * @phpstan-ignore-next-line
         */
        yield 'user entity' => [User::class, UserRepository::class];
    }
}
