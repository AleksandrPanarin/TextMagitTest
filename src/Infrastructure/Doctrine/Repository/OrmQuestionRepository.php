<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Question;
use App\Domain\QuestionRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DomainException;

/**
 * @extends ServiceEntityRepository<Question>
 */
class OrmQuestionRepository extends ServiceEntityRepository implements QuestionRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findAllQuestions(int $maxResult): array
    {
        return $this->createQueryBuilder('q')->setMaxResults($maxResult)->getQuery()->getResult();
    }

    public function getById(int $id): Question
    {
        $question = $this->find($id);

        if ($question === null) {
            throw new DomainException(sprintf('Question with id "%d" does not exist.', $id));
        }
        return $question;
    }
}
