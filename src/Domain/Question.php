<?php

namespace App\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'questions')]
final class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $title;

    #[OneToMany(targetEntity: Answer::class, mappedBy: 'question', cascade: ['all'])]
    private Collection $answers;

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->answers = new ArrayCollection();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function addAnswers(Answer $answer): void
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }
    }

    public function shuffleAnswers(): ArrayCollection
    {
        $answers = $this->answers->toArray();
        shuffle($answers);

        return new ArrayCollection($answers);
    }
}
