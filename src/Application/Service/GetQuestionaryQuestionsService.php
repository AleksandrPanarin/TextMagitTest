<?php

namespace App\Application\Service;

use App\Application\Dto\AnswerViewDto;
use App\Application\Dto\QuestionViewDto;
use App\Domain\Questionary\QuestionaryAnswer;
use App\Domain\Questionary\QuestionaryQuestion;
use App\Domain\QuestionaryRepository;

final readonly class GetQuestionaryQuestionsService
{
    public function __construct(
        private QuestionaryRepository $questionaries
    )
    {
    }

    public function execute(GetQuestionaryQuestions $query): array
    {
        $questionary = $this->questionaries->getByUuid($query->questionaryUuid());

        $questionViews = [];
        /** @var QuestionaryQuestion $question */
        foreach ($questionary->questions() as $question) {
            $questionViewDto = new QuestionViewDto();
            $questionViewDto->id = $question->id();
            $questionViewDto->title = $question->title();

            /** @var QuestionaryAnswer $answer */
            foreach ($question->answers() as $answer) {
                $answerViewDto = new AnswerViewDto();
                $answerViewDto->id = $answer->id();
                $answerViewDto->title = $answer->title();

                $questionViewDto->answers[] = $answerViewDto;
            }

            $questionViews[] = $questionViewDto;
        }

        return $questionViews;
    }
}