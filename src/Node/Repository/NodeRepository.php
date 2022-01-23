<?php

declare(strict_types=1);

namespace App\Node\Repository;

use App\Node\Entity\Node;
use App\Node\Gateway\NodeGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Node>
 * @template-implements NodeGateway<Node>
 */
final class NodeRepository extends ServiceEntityRepository implements NodeGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Node::class);
    }
}
