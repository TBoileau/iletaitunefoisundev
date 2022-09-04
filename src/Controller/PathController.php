<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use IncentiveFactory\Domain\Path\BeginTraining\BeginningOfTraining;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\GetCoursesByTraining\TrainingCourses;
use IncentiveFactory\Domain\Path\GetTrainingBySlug\TrainingSlug;
use IncentiveFactory\Domain\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\Voter\TrainingVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paths', name: 'path_')]
final class PathController extends AbstractController
{
    #[Route('/trainings', name: 'trainings', methods: [Request::METHOD_GET])]
    public function trainings(): Response
    {
        /** @var array<array-key, Training> $trainings */
        $trainings = $this->fetch(new ListOfTrainings());

        return $this->render('path/trainings.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/trainings/{slug}', name: 'training', methods: [Request::METHOD_GET])]
    public function training(string $slug): Response
    {
        /** @var ?Training $training */
        $training = $this->fetch(new TrainingSlug($slug));

        if (null === $training) {
            throw $this->createNotFoundException('Training not found');
        }

        /** @var array<array-key, Course> $courses */
        $courses = $this->fetch(new TrainingCourses($training));

        return $this->render('path/training.html.twig', [
            'training' => $training,
            'courses' => $courses,
        ]);
    }

    #[Route('/trainings/{slug}/begin', name: 'begin', methods: [Request::METHOD_GET])]
    public function begin(string $slug): Response
    {
        /** @var Training $training */
        $training = $this->fetch(new TrainingSlug($slug));

        $this->denyAccessUnlessGranted(TrainingVoter::BEGIN, $training);

        /** @var Player $player */
        $player = $this->getPlayer();

        $this->execute(new BeginningOfTraining($player, $training));

        return $this->redirectToRoute('path_training', ['slug' => $slug]);
    }
}
