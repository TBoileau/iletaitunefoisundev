<?php

declare(strict_types=1);

namespace App\Node\Repository;

use App\Node\Entity\Course;
use App\Node\Gateway\CourseGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Course>
 * @template-implements CourseGateway<Course>
 */
final class CourseRepository extends ServiceEntityRepository implements CourseGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }
}
