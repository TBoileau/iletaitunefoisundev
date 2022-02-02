<?php

declare(strict_types=1);

namespace App\Content\Doctrine\Repository;

use App\Content\Entity\Course;
use App\Content\Gateway\CourseGateway;
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
