<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @return Question[]
     */
    public function findByType(string $evaluationType): array
    {
        return $this->createQueryBuilder('q')
            ->where('q.evaluationType = :type')
            ->andWhere('q.isActive = true')
            ->setParameter('type', $evaluationType)
            ->orderBy('q.category', 'ASC')
            ->addOrderBy('q.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get distinct categories for a given evaluation type.
     * @return string[]
     */
    public function findCategories(string $evaluationType): array
    {
        return $this->createQueryBuilder('q')
            ->select('DISTINCT q.category')
            ->where('q.evaluationType = :type')
            ->andWhere('q.isActive = true')
            ->andWhere('q.category IS NOT NULL')
            ->setParameter('type', $evaluationType)
            ->orderBy('q.category', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
