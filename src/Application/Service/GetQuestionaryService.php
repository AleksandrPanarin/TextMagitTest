<?php

namespace App\Application\Service;

use App\Application\Dto\QuestionaryViewDto;
use App\Application\Dto\QuestionViewDto;
use App\Domain\QuestionaryRepository;

final readonly class GetQuestionaryService
{
    public function __construct(
        private QuestionaryRepository $questionaries
    )
    {
    }

    public function execute(GetQuestionary $query): QuestionaryViewDto
    {
        $questionary = $this->questionaries->getByUuid($query->questionaryUuid());

        $questionWithIncorrectAnswers = $questionary->findQuestionWithIncorrectAnswers();
        $questionWithCorrectAnswers = $questionary->findQuestionWithCorrectAnswers();

        $questionaryViewDto = new QuestionaryViewDto();
        foreach ($questionWithIncorrectAnswers as $incorrectQuestion) {
            $questionDto = new QuestionViewDto();
            $questionDto->id = $incorrectQuestion->id();
            $questionDto->title = $incorrectQuestion->title();
            $questionaryViewDto->questionsWithIncorrectAnswers[] = $questionDto;
        }

        foreach ($questionWithCorrectAnswers as $correctQuestion) {
            $questionDto = new QuestionViewDto();
            $questionDto->id = $correctQuestion->id();
            $questionDto->title = $correctQuestion->title();
            $questionaryViewDto->questionsWithCorrectAnswers[] = $questionDto;
        }

        return $questionaryViewDto;
    }
}