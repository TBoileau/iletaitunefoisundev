<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Path\Path;

final class CourseLogRepository implements CourseLogGateway
{
    public function begin(CourseLog $courseLog): void
    {
        // TODO: Implement begin() method.
    }

    public function complete(CourseLog $courseLog): void
    {
        // TODO: Implement complete() method.
    }

    public function hasAlreadyBegan(Path $path, Course $course): bool
    {
        // TODO: Implement hasAlreadyBegan() method.
        return false;
    }

    public function countCoursesCompletedByPath(Path $path): int
    {
        // TODO: Implement countCoursesCompletedByPath() method.
        return 0;
    }
}
