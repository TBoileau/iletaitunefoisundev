<?php

declare(strict_types=1);

namespace App\Content\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Content\Controller\StartQuizSessionController;
use App\Content\Doctrine\Repository\QuizRepository;
use App\Content\Entity\Quiz\Session;
use App\Content\UseCase\StartQuizSession\StartQuizSessionInput;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get',
        'start' => [
            'messenger' => 'input',
            'controller' => StartQuizSessionController::class,
            'output' => Session::class,
            'input' => StartQuizSessionInput::class,
            'status' => Response::HTTP_CREATED,
            'method' => Request::METHOD_POST,
            'openapi_context' => [
                'summary' => 'Start a quiz session',
                'description' => 'Creates a Session resource',
            ],
            'path' => '/quizzes/{id}/start',
            'security' => 'is_granted("ROLE_PLAYER") and is_granted("respond", object)',
            'denormalization_context' => ['groups' => ['write']],
            'normalization_context' => ['groups' => ['read']],
        ],
    ],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/content',
)]
#[Entity(repositoryClass: QuizRepository::class)]
class Quiz extends Node
{
    /**
     * @var Collection<int, Question>
     */
    #[OneToMany(mappedBy: 'quiz', targetEntity: Question::class)]
    #[Groups('read')]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }
}
