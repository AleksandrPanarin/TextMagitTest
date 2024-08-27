<?php

namespace App\Application\Service;


final class FillUpQuestionaryWithAnswers
{
    private string $questionaryUuid;

    private array $answers = [];

    public function __construct(string $questionaryUuid, array $answers)
    {
        $this->questionaryUuid = $questionaryUuid;
        $this->answers = $answers;
    }

    public function questionaryUuid(): string
    {
        return $this->questionaryUuid;
    }

    public function answers(): array
    {
        return $this->answers;
    }
}