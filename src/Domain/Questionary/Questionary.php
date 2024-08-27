<?php

namespace App\Domain\Questionary;


use App\Infrastructure\Doctrine\Type\QuestionWithAnswerCollectionType;
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

    #[ORM\Column(type: QuestionWithAnswerCollectionType::QUESTION_WITH_ANSWER_COLLECTION_TYPE)]
    private Collection $multipleChoiceQuestions;

    #[ORM\Column(type: QuestionWithAnswerCollectionType::QUESTION_WITH_ANSWER_COLLECTION_TYPE)]
    private Collection $questionsWithAnswersUser;

    public function __construct(UuidInterface $uuid, string $fullName)
    {
        $this->fullName = $fullName;
        $this->uuid = $uuid;
        $this->multipleChoiceQuestions = new ArrayCollection();
        $this->questionsWithAnswersUser = new ArrayCollection();
    }

    public function addMultipleChoiceQuestion(QuestionaryQuestionWithAnswer $multipleChoiceQuestion): void
    {
        if (!$this->multipleChoiceQuestions->contains($multipleChoiceQuestion)) {
            $this->multipleChoiceQuestions->add($multipleChoiceQuestion);
            $this->multipleChoiceQuestions = new ArrayCollection($this->multipleChoiceQuestions->toArray());
        }
    }

    public function addQuestionWithAnswersUser(QuestionaryQuestionWithAnswer $answer): void
    {
        if (!$this->questionsWithAnswersUser->contains($answer)) {
            $this->questionsWithAnswersUser->add($answer);
            $this->questionsWithAnswersUser = new ArrayCollection($this->questionsWithAnswersUser->toArray());
        }
    }


    public function multipleChoiceQuestions(): Collection
    {
        return $this->multipleChoiceQuestions;
    }

    public function questionsWithAnswersUser(): Collection
    {
        return $this->questionsWithAnswersUser;
    }

    public function findQuestionById(int $id): ?QuestionaryQuestionWithAnswer
    {
        return $this->multipleChoiceQuestions->filter(fn(QuestionaryQuestionWithAnswer $question) => $question->id() === $id)->first();
    }

    /** @return QuestionaryQuestionWithAnswer[] */
    public function findQuestionWithIncorrectAnswers(): array
    {
        return $this->questionsWithAnswersUser->filter(function (QuestionaryQuestionWithAnswer $question): bool {
            /** @var QuestionaryAnswer $answer */
            foreach ($question->answers() as $answer) {
                if (!$answer->isCorrect()) {
                    return true;
                }
            }
            return false;
        })->toArray();
    }

    /** @return QuestionaryQuestionWithAnswer[] */
    public function findQuestionWithCorrectAnswers(): array
    {
        return $this->questionsWithAnswersUser->filter(function (QuestionaryQuestionWithAnswer $question): bool {
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