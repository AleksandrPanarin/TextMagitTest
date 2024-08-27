<?php

namespace App\Application\Service;

final class GetQuestionary
{
    private string $questionaryUuid;

    public function __construct(string $questionaryUuid)
    {
        $this->questionaryUuid = $questionaryUuid;
    }

    public function questionaryUuid(): string
    {
        return $this->questionaryUuid;
    }
}