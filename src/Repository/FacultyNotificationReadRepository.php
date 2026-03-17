<?php

namespace App\Repository;

use App\Entity\FacultyNotificationRead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FacultyNotificationRead>
 */
class FacultyNotificationReadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacultyNotificationRead::class);
    }

    /**
     * Get all read evaluation IDs for a given user.
     * @return int[]
     */
    public function findReadEvaluationIds(int $userId): array
    {
        $rows = $this->createQueryBuilder('r')
            ->select('IDENTITY(r.evaluationPeriod) AS epId')
            ->andWhere('r.user = :uid')
            ->setParameter('uid', $userId)
            ->getQuery()
            ->getScalarResult();

        return array_map(fn($r) => (int) $r['epId'], $rows);
    }
}
