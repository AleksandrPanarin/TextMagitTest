<?php

namespace App\Application\Dto;

final class QuestionViewDto
{
    public ?int $id = null;

    public string $title = '';
    public array $answers = [];
}