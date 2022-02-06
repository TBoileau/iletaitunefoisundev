<?php

declare(strict_types=1);

namespace App\Content\Doctrine\DataFixtures;

use App\Content\Entity\Answer;
use App\Content\Entity\Question;
use App\Content\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class QuizFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 125; ++$i) {
            $quiz = $this->createQuiz($i);
            $manager->persist($quiz);

            for ($j = 1; $j <= 5; ++$j) {
                $manager->persist($this->createQuestion($j, $quiz));
            }
        }

        $manager->flush();
    }

    private function createQuiz(int $index): Quiz
    {
        $quiz = new Quiz();
        $quiz->setTitle(sprintf('Quiz %d', $index));
        $quiz->setSlug(sprintf('quiz-%d', $index));

        return $quiz;
    }

    private function createQuestion(int $index, Quiz $quiz): Question
    {
        $question = new Question();
        $question->setLabel(sprintf('Question %d', $index));
        $question->setContent(sprintf('Content %d', $index));
        $question->setQuiz($quiz);

        for ($i = 1; $i <= 3; ++$i) {
            $answer = new Answer();
            $answer->setLabel(sprintf('Réponse %d', $i));
            $answer->setGood(0 === $i % 2);
            $answer->setContent(sprintf('Content %d', $i));
            $question->addAnswer($answer);
        }

        return $question;
    }
}
