<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use IncentiveFactory\Domain\Course\Course;
use IncentiveFactory\Domain\Course\CourseGateway;

final class CourseRepository implements CourseGateway
{
    public function getCourseBySlug(string $slug): ?Course
    {
        // TODO: Implement findOneBySlug() method.
        return null;
    }
}
