<?php

declare(strict_types=1);

namespace App\Content\Doctrine\Repository;

use App\Content\Entity\Quiz\Response;
use App\Content\Gateway\ResponseGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Response>
 * @template-implements ResponseGateway<Response>
 */
final class ResponseRepository extends ServiceEntityRepository implements ResponseGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }

    public function submit(Response $response): void
    {
        $this->_em->flush();
    }

    public function getResponseById(int $id): ?Response
    {
        /** @var ?Response $response */
        $response = $this->createQueryBuilder('r')
            ->addSelect('q')
            ->addSelect('a')
            ->join('r.question', 'q')
            ->join('q.answers', 'a')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();

        return $response;
    }
}
