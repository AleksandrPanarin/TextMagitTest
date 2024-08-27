<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Questionary\QuestionaryAnswer;
use App\Domain\Questionary\QuestionaryQuestionWithAnswer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class QuestionWithAnswerCollectionType extends Type
{
    public const string QUESTION_WITH_ANSWER_COLLECTION_TYPE = 'question_with_answer_collection_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "JSON";
    }

    /**
     * @param Collection $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        $data = [];
        /** @var QuestionaryQuestionWithAnswer $question */
        foreach ($value->toArray() as $question) {
            $answerData = [];
            /** @var QuestionaryAnswer $answer */
            foreach ($question->answers() as $answer) {
                $answerData[] = [
                    'id' => $answer->id(),
                    'title' => $answer->title(),
                    'is_correct' => $answer->isCorrect(),
                ];
            }
            $questionData = [
                'id' => $question->id(),
                'title' => $question->title(),
                'answers' => $answerData,
            ];

            $data[] = $questionData;
        }

        return json_encode($data);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Collection
    {
        if ($value === null) {
            return null;
        }

        $data = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConversionException('Invalid JSON data');
        }

        $questions = [];
        foreach ($data as $questionData) {
            $question = new QuestionaryQuestionWithAnswer($questionData['id'], $questionData['title']);
            foreach ($questionData['answers'] as $answerData) {
                $question->addAnswer(new QuestionaryAnswer($answerData['id'], $answerData['title'], $answerData['is_correct']));
            }
            $questions[] = $question;
        }

        return new ArrayCollection($questions);
    }

    public function getName(): string
    {
        return self::QUESTION_WITH_ANSWER_COLLECTION_TYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}