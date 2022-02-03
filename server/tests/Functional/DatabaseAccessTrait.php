<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method object findOneBy(string $entity, array $params)
 */
trait DatabaseAccessTrait
{
    /**
     * @var array<class-string, ServiceEntityRepository<object>>
     */
    private array $repositories = [];

    private EntityManagerInterface $entityManager;

    private function init(Client $client): void
    {
        /* @phpstan-ignore-next-line */
        $this->entityManager = $client->getContainer()->get(EntityManagerInterface::class);
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        /** @var class-string $entity */
        $entity = $arguments[0];
        $repository = $this->getRepository($entity);

        /** @var array<array-key, mixed> $args */
        $args = $arguments[1] ?? [];

        /** @var callable $callback */
        $callback = [$repository, $name];

        return call_user_func_array($callback, $args);
    }

    /**
     * @param class-string $entity
     *
     * @phpstan-ignore-next-line
     */
    private function getRepository(string $entity): ServiceEntityRepository
    {
        if (!isset($this->repositories[$entity])) {
            /** @var ServiceEntityRepository<object> $repository */
            $repository = $this->entityManager->getRepository($entity);
            $this->repositories[$entity] = $repository;
        }

        return $this->repositories[$entity];
    }
}
