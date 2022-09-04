<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use IncentiveFactory\Domain\Path\BeginTraining\BeginningOfTraining;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\GetCourseBySlug\CourseSlug;
use IncentiveFactory\Domain\Path\GetCoursesByTraining\TrainingCourses;
use IncentiveFactory\Domain\Path\GetPathById\PathId;
use IncentiveFactory\Domain\Path\GetPathsByPlayer\PlayerPaths;
use IncentiveFactory\Domain\Path\GetTrainingBySlug\TrainingSlug;
use IncentiveFactory\Domain\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\Voter\PathVoter;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\Voter\TrainingVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paths', name: 'path_')]
final class PathController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(name: 'list', methods: [Request::METHOD_GET])]
    public function list(): Response
    {
        /** @var Player $player */
        $player = $this->getPlayer();

        /** @var array<array-key, Path> $paths */
        $paths = $this->fetch(new PlayerPaths($player));

        return $this->render('path/list.html.twig', [
            'paths' => $paths,
        ]);
    }

    #[Route('/trainings', name: 'trainings', methods: [Request::METHOD_GET])]
    public function trainings(): Response
    {
        /** @var array<array-key, Training> $trainings */
        $trainings = $this->fetch(new ListOfTrainings());

        return $this->render('path/trainings.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/courses/{slug}', name: 'course', methods: [Request::METHOD_GET])]
    public function course(string $slug): Response
    {
        /** @var ?Course $course */
        $course = $this->fetch(new CourseSlug($slug));

        if (null === $course) {
            throw $this->createNotFoundException('Course not found');
        }

        return $this->render('path/course.html.twig', [
            'course' => $course,
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

    #[Route('/{id}', name: 'show', methods: [Request::METHOD_GET])]
    public function show(string $id): Response
    {
        /** @var ?Path $path */
        $path = $this->fetch(new PathId($id));

        if (null === $path) {
            throw $this->createNotFoundException('Path not found');
        }

        $this->denyAccessUnlessGranted(PathVoter::SHOW, $path);

        return $this->render('path/show.html.twig', [
            'path' => $path,
        ]);
    }
}
