<?php

declare(strict_types=1);

namespace App\Content\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Content\Doctrine\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get'],
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
