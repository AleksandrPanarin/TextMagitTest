<?php

namespace App\Application\Service;

final class CreateQuestionary
{
    private string $fullName;

    public function __construct(string $fullName)
    {
        $this->fullName = $fullName;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }
}