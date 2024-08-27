<?php

namespace App\Domain\Questionary;


use App\Infrastructure\Doctrine\Type\QuestionAnswerCollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity()]
#[ORM\Table(name: 'questionaries')]
final class Questionary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $fullName;

    #[ORM\Column(nullable: false, unique: false)]
    private string $uuid;

    #[ORM\Column(type: QuestionAnswerCollectionType::QUESTION_ANSWER_COLLECTION)]
    private Collection $questions;

    #[ORM\Column(type: QuestionAnswerCollectionType::QUESTION_ANSWER_COLLECTION)]
    private Collection $answers;

    public function __construct(UuidInterface $uuid, string $fullName)
    {
        $this->fullName = $fullName;
        $this->uuid = $uuid;
        $this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function addQuestion(QuestionaryQuestion $question): void
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $this->questions = new ArrayCollection($this->questions->toArray());
        }
    }

    public function addAnswer(QuestionaryQuestion $answer): void
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $this->answers = new ArrayCollection($this->answers->toArray());
        }
    }


    public function questions(): Collection
    {
        return $this->questions;
    }

    public function answers(): Collection
    {
        return $this->answers;
    }

    public function findQuestionById(int $id): ?QuestionaryQuestion
    {
        return $this->questions->filter(fn(QuestionaryQuestion $question) => $question->id() === $id)->first();
    }

    /** @return QuestionaryQuestion[] */
    public function findQuestionWithIncorrectAnswers(): array
    {
        return $this->answers->filter(function (QuestionaryQuestion $question): bool {
            /** @var QuestionaryAnswer $answer */
            foreach ($question->answers() as $answer) {
                if (!$answer->isCorrect()) {
                    return true;
                }
            }
            return false;
        })->toArray();
    }

    /** @return QuestionaryQuestion[] */
    public function findQuestionWithCorrectAnswers(): array
    {
        return $this->answers->filter(function (QuestionaryQuestion $question): bool {
            /** @var QuestionaryAnswer $answer */
            foreach ($question->answers() as $answer) {
                if (!$answer->isCorrect()) {
                    return false;
                }
            }
            return true;
        })->toArray();
    }
}