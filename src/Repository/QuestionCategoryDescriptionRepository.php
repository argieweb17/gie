<?php

namespace App\Repository;

use App\Entity\QuestionCategoryDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionCategoryDescription>
 */
class QuestionCategoryDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionCategoryDescription::class);
    }

    /**
     * Return descriptions indexed by category name for a given evaluation type.
     * @return array<string, string>
     */
    public function findDescriptionsByType(string $evaluationType): array
    {
        $rows = $this->createQueryBuilder('d')
            ->where('d.evaluationType = :type')
            ->setParameter('type', $evaluationType)
            ->getQuery()
            ->getResult();

        $map = [];
        foreach ($rows as $row) {
            $map[$row->getCategory()] = $row->getDescription();
        }
        return $map;
    }
}
