<?php

namespace App\Domain\Questionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class QuestionaryQuestionWithAnswer
{
    private int $id;

    private string $title;

    private Collection $answers;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
        $this->answers = new ArrayCollection();
    }

    public function addAnswer(QuestionaryAnswer $answers): void
    {
        if (!$this->answers->contains($answers)) {
            $this->answers->add($answers);
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function answers(): Collection
    {
        return $this->answers;
    }

    public function removeUnmarkedAnswersByIds(array $answerIds): void
    {
        $this->answers = new ArrayCollection($this->findAnswersByIds($answerIds));
    }

    private function findAnswersByIds(array $answerIds): array
    {
        return $this->answers->filter(fn(QuestionaryAnswer $answer) => in_array($answer->id(), $answerIds))->toArray();
    }
}