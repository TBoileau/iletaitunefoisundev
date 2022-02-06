<?php

declare(strict_types=1);

namespace App\Content\Entity;

use App\Content\Doctrine\Repository\ResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private ?int $id = null;

    #[ManyToOne(targetEntity: PlayerQuiz::class, inversedBy: 'responses')]
    #[JoinColumn(nullable: false)]
    private PlayerQuiz $playerQuiz;

    #[ManyToOne(targetEntity: Question::class)]
    #[JoinColumn(nullable: false)]
    #[Groups('read')]
    private Question $question;

    /**
     * @var Collection<int, Answer>
     */
    #[ManyToMany(targetEntity: Answer::class)]
    #[JoinTable(name: 'response_answers')]
    #[Groups('read')]
    private Collection $answers;

    #[Column(type: Types::BOOLEAN)]
    #[Groups('read')]
    private bool $valid;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getPlayerQuiz(): PlayerQuiz
    {
        return $this->playerQuiz;
    }

    public function setPlayerQuiz(PlayerQuiz $playerQuiz): void
    {
        $this->playerQuiz = $playerQuiz;
    }
}
