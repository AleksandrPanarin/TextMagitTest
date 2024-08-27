<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Answer;
use App\Domain\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $data = [
            '1 + 1 = ' => [
                ['title' => '3', 'isCorrect' => false],
                ['title' => '2', 'isCorrect' => true],
                ['title' => '0', 'isCorrect' => false],
            ],
            '2 + 2 = ' => [
                ['title' => '4', 'isCorrect' => true],
                ['title' => '3 + 1', 'isCorrect' => true],
                ['title' => '10', 'isCorrect' => false],
            ],
            '3 + 3 = ' => [
                ['title' => '1 + 5', 'isCorrect' => true],
                ['title' => '1', 'isCorrect' => false],
                ['title' => '6', 'isCorrect' => true],
                ['title' => '2 + 4', 'isCorrect' => true],
            ],
            '4 + 4 = ' => [
                ['title' => '8', 'isCorrect' => true],
                ['title' => '4', 'isCorrect' => false],
                ['title' => '0', 'isCorrect' => false],
                ['title' => '0 + 8', 'isCorrect' => true],
            ],
            '5 + 5 = ' => [
                ['title' => '6', 'isCorrect' => false],
                ['title' => '18', 'isCorrect' => false],
                ['title' => '10', 'isCorrect' => true],
                ['title' => '9', 'isCorrect' => false],
                ['title' => '0', 'isCorrect' => false],
            ],
            '6 + 6 = ' => [
                ['title' => '3', 'isCorrect' => false],
                ['title' => '9', 'isCorrect' => false],
                ['title' => '0', 'isCorrect' => false],
                ['title' => '12', 'isCorrect' => true],
                ['title' => '5 + 7', 'isCorrect' => true],
            ],
            '7 + 7 = ' => [
                ['title' => '5', 'isCorrect' => false],
                ['title' => '14', 'isCorrect' => true],
            ],
            '8 + 8 = ' => [
                ['title' => '16', 'isCorrect' => true],
                ['title' => '12', 'isCorrect' => false],
                ['title' => '9', 'isCorrect' => false],
                ['title' => '5', 'isCorrect' => false],
            ],
            '9 + 9 = ' => [
                ['title' => '18', 'isCorrect' => true],
                ['title' => '9', 'isCorrect' => false],
                ['title' => '17 + 1', 'isCorrect' => true],
                ['title' => '2 + 16', 'isCorrect' => false],
            ],
            '10 + 10 =' => [
                ['title' => '0', 'isCorrect' => false],
                ['title' => '2', 'isCorrect' => false],
                ['title' => '8', 'isCorrect' => false],
                ['title' => '20', 'isCorrect' => true],
            ],
        ];

        foreach ($data as $questionTitle => $answerData) {
            $question = new Question($questionTitle);
            foreach ($answerData as $answer) {
                $question->addAnswers(new Answer($answer['title'], $answer['isCorrect']));
            }
            $manager->persist($question);
        }


        $manager->flush();
    }
}
