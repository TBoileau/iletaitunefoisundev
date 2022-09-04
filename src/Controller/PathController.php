<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use IncentiveFactory\Domain\Path\GetTrainingBySlug\TrainingSlug;
use IncentiveFactory\Domain\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Domain\Path\Training;
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
        /** @var Training $training */
        $training = $this->fetch(new TrainingSlug($slug));

        return $this->render('path/training.html.twig', [
            'training' => $training,
        ]);
    }
}
