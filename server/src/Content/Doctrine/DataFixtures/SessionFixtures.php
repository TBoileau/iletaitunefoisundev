<?php

declare(strict_types=1);

namespace App\Content\Doctrine\DataFixtures;

use App\Adventure\Doctrine\DataFixtures\PlayerFixtures;
use App\Adventure\Entity\Player;
use App\Content\Entity\Answer;
use App\Content\Entity\Quiz;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Player> $players */
        $players = $manager->getRepository(Player::class)->findAll();

        /** @var array<array-key, Quiz> $quizzes */
        $quizzes = $manager->getRepository(Quiz::class)->findBy([], [], 50, 75);

        foreach ($quizzes as $quiz) {
            foreach ($players as $player) {
                $session = new Quiz\Session();
                $session->setQuiz($quiz);
                $session->setPlayer($player);
                $session->setStartedAt(new DateTimeImmutable());
                $session->setFinishedAt($session->getStartedAt()->add(new DateInterval('PT1H')));

                foreach ($quiz->getQuestions() as $i => $question) {
                    $response = new Quiz\Response();
                    $response->setSession($session);
                    $response->setRespondedAt($session->getStartedAt()->add(new DateInterval('PT10M')));
                    $response->setValid(0 === $i % 2);
                    $response->setQuestion($question);
                    /** @var Answer $answer */
                    $answer = $question->getAnswers()->get(0 === $i % 2 ? 1 : 0);

                    $response->getAnswers()->add($answer);
                    $session->getResponses()->add($response);
                }

                $manager->persist($session);
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [PlayerFixtures::class, QuizFixtures::class];
    }
}
