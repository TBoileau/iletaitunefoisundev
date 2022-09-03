<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Repository;

use IncentiveFactory\Domain\Course\Course;
use IncentiveFactory\Domain\Course\CourseLog;
use IncentiveFactory\Domain\Course\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;

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

    public function hasAlreadyBegan(PlayerInterface $player, Course $course): bool
    {
        // TODO: Implement hasAlreadyBegan() method.
        return false;
    }
}
