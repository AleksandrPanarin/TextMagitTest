<?php

namespace App\Domain;

interface QuestionRepository
{
    public function findAllQuestions(int $maxResult): array;

    public function getById(int $id): Question;
}