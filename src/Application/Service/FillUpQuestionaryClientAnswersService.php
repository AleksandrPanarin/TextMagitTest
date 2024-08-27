<?php

namespace App\Application\Service;

use App\Domain\Questionary\QuestionaryQuestionWithAnswer;
use App\Domain\QuestionaryRepository;
use RuntimeException;

final readonly class FillUpQuestionaryClientAnswersService
{
    public function __construct(
        private QuestionaryRepository $questionaries,
    )
    {
    }

    public function execute(FillUpQuestionaryClientAnswers $command): void
    {
        $answers = $command->answers();
        if (empty($answers)) {
            throw new RuntimeException('No answers passed.');
        }

        $questionary = $this->questionaries->getByUuid($command->questionaryUuid());

        if(!$questionary->questionsWithAnswersUser()->isEmpty()){
            throw new RuntimeException('Questionary already passed.');
        }

        /** @var QuestionaryQuestionWithAnswer $questionWithAnswer */
        foreach ($questionary->multipleChoiceQuestions() as $questionWithAnswer) {
            if (!array_key_exists($questionWithAnswer->id(), $answers) || empty($answers[$questionWithAnswer->id()])) {
                throw new RuntimeException('Fill up full questionary.');
            }
        }

        foreach ($command->answers() as $questionId => $answerIds) {
            $questionWithAnswer = clone $questionary->findQuestionById($questionId);
            $questionWithAnswer->removeUnmarkedAnswersByIds($answerIds);
            $questionary->addQuestionWithAnswersUser($questionWithAnswer);
        }

        $this->questionaries->update($questionary);
    }
}