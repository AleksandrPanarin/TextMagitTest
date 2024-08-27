<?php

namespace App\Application\Service;

use App\Domain\Questionary\QuestionaryAnswer;
use App\Domain\Questionary\QuestionaryQuestion;
use App\Domain\QuestionaryRepository;
use App\Domain\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use RuntimeException;

final readonly class FillUpQuestionaryWithAnswersService
{
    public function __construct(
        private QuestionaryRepository $questionaries,
    )
    {
    }

    public function execute(FillUpQuestionaryWithAnswers $command): void
    {
        $answers = $command->answers();
        if (empty($answers)) {
            throw new RuntimeException('No answers passed.');
        }

        $questionary = $this->questionaries->getByUuid($command->questionaryUuid());

        if(!$questionary->answers()->isEmpty()){
            throw new RuntimeException('Questionary already passed.');
        }

        /** @var QuestionaryQuestion $question */
        foreach ($questionary->questions() as $question) {
            if (!array_key_exists($question->id(), $answers) || empty($answers[$question->id()])) {
                throw new RuntimeException('Fill up full questionary.');
            }
        }

        foreach ($command->answers() as $questionId => $answerIds) {
            $question = clone $questionary->findQuestionById($questionId);
            $question->removeUnmarkedAnswersByIds($answerIds);
            $questionary->addAnswer($question);
        }

        $this->questionaries->update($questionary);
    }
}