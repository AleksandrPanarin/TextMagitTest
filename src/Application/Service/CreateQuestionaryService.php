<?php

namespace App\Application\Service;

use App\Domain\Question;
use App\Domain\Questionary\Questionary;
use App\Domain\Questionary\QuestionaryAnswer;
use App\Domain\Questionary\QuestionaryQuestion;
use App\Domain\QuestionaryRepository;
use App\Domain\QuestionRepository;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final readonly class CreateQuestionaryService
{
    private const int MAX_QUESTIONS = 20;

    public function __construct(
        private QuestionaryRepository $questionaries,
        private QuestionRepository    $questions,
    )
    {
    }

    public function execute(CreateQuestionary $command): string
    {
        if (trim($command->fullName()) === '') {
            throw new RuntimeException('Full name can not be empty.');
        }

        $questionaryUuid = Uuid::uuid4();
        $this->questionaries->add(new Questionary($questionaryUuid, $command->fullName()));
        $this->fillUpQuestionnaireWithQuestions($questionaryUuid);

        return $questionaryUuid->toString();
    }

    private function fillUpQuestionnaireWithQuestions(string $uuid): void
    {
        $questionary = $this->questionaries->getByUuid($uuid);

        $questions = $this->questions->findAllQuestions(self::MAX_QUESTIONS);
        shuffle($questions);

        /** @var Question $question */
        foreach ($questions as $question) {
            $questionaryQuestion = new QuestionaryQuestion($question->id(), $question->title());

            foreach ($question->shuffleAnswers() as $answer) {
                $questionaryQuestion->addAnswer(
                    new QuestionaryAnswer($answer->id(), $answer->title(), $answer->isCorrect())
                );
            }
            $questionary->addQuestion($questionaryQuestion);
        }
        $this->questionaries->update($questionary);
    }
}