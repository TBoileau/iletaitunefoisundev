<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use IncentiveFactory\Domain\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Domain\Path\Training;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paths', name: 'path_')]
final class PathController extends AbstractController
{
    #[Route('/trainings', name: 'trainings')]
    public function trainings(): Response
    {
        /** @var array<array-key, Training> $trainings */
        $trainings = $this->fetch(new ListOfTrainings());

        return $this->render('path/list_trainings.html.twig', [
            'trainings' => $trainings,
        ]);
    }
}
