<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Path\Training;

final class CourseRepository implements CourseGateway
{
    public function getCourseBySlug(string $slug): ?Course
    {
        // TODO: Implement findOneBySlug() method.
        return null;
    }

    public function countCoursesByTraining(Training $training): int
    {
        // TODO: Implement countCoursesByTraining() method.
        return 0;
    }

    public function getCoursesByTraining(Training $training): array
    {
        // TODO: Implement getCoursesByTraining() method.
        return [];
    }
}
