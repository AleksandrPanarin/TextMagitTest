<?php

namespace App\Application\Dto;

final class QuestionaryViewDto
{
    /** @var QuestionViewDto[] */
    public array $questionsWithCorrectAnswers = [];

    /** @var QuestionViewDto[] */
    public array $questionsWithIncorrectAnswers = [];

}