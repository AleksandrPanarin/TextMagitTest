<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Question;
use App\Domain\Questionary\Questionary;
use App\Domain\QuestionaryRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DomainException;

/**
 * @extends ServiceEntityRepository<Question>
 */
class OrmQuestionaryRepository extends ServiceEntityRepository implements QuestionaryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questionary::class);
    }

    public function getByUuid(string $uuid): Questionary
    {
        $questionary = $this->findOneBy(['uuid' => $uuid]);

        if ($questionary === null) {
            throw new DomainException(sprintf('Question with id "%s" does not exist.', $uuid));
        }

        return $questionary;
    }

    public function add(Questionary $questionary): void
    {
        $this->getEntityManager()->persist($questionary);
        $this->getEntityManager()->flush();
    }

    public function update(Questionary $questionary): void
    {
       $this->getEntityManager()->persist($questionary);
       $this->getEntityManager()->flush();
    }
}
