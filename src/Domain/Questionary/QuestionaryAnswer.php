<?php

namespace App\Domain\Questionary;

final class QuestionaryAnswer
{
    private int $id;
    private string $title;
    private bool $isCorrect;

    public function __construct(int $id, string $title, bool $isCorrect)
    {

        $this->id = $id;
        $this->title = $title;
        $this->isCorrect = $isCorrect;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }
}