<?php

namespace App\Domain;

use App\Domain\Questionary\Questionary;

interface QuestionaryRepository
{
    public function getByUuid(string $uuid): Questionary;

    public function add(Questionary $questionary): void;
    public function update(Questionary $questionary): void;
}