<?php

declare(strict_types=1);

namespace App\Content\Doctrine\DataFixtures;

use App\Content\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 125; ++$i) {
            $manager->persist($this->createCourse($i));
        }

        $manager->flush();
    }

    private function createCourse(int $index): Course
    {
        $course = new Course();
        $course->setTitle(sprintf('Course %d', $index));
        $course->setSlug(sprintf('course-%d', $index));
        $course->setYoutubeUrl('https://www.youtube.com/watch?v=-S94RNjjb4I');
        $course->setDescription(sprintf('Description %s', $index));
        $course->setContent(sprintf('Content %s', $index));

        return $course;
    }
}
