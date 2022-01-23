<?php

declare(strict_types=1);

namespace App\Node\DataFixtures;

use App\Node\Entity\Course;
use App\Node\Entity\History;
use App\Node\Entity\Node;
use App\Security\DataFixtures\UserFixtures;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class HistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, User> $users */
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $startedAt = new DateTimeImmutable('2022-01-01 00:00:00');

            /** @var Course $course */
            $course = $manager->getRepository(Course::class)->findOneBy(['slug' => 'course-1']);

            for ($i = 1; $i <= 25; ++$i) {
                $history = $this->createHistory($user, $course, $startedAt, $i < 25);
                $manager->persist($history);

                /** @var Course $course */
                $course = $course->getSiblings()->first();

                if (null !== $history->getFinishedAt()) {
                    $startedAt = $history->getFinishedAt()->add(new \DateInterval('PT1M'));
                }
            }

            $manager->flush();
        }
    }

    private function createHistory(
        User $user,
        Node $node,
        DateTimeImmutable $startedAt,
        bool $finished = true
    ): History {
        $history = new History();
        $history->setUser($user);
        $history->setNode($node);
        $history->setStartedAt($startedAt);

        if ($finished) {
            $history->setFinishedAt($history->getStartedAt()->add(new \DateInterval('PT59M')));
            $history->setGrade(rand(1, 5));
            $history->setComment('Commentaire');
        }

        return $history;
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class, CourseFixtures::class];
    }
}
