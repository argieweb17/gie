<?php

namespace App\Repository;

use App\Entity\EvaluationPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EvaluationPeriod>
 */
class EvaluationPeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluationPeriod::class);
    }

    /**
     * Find currently open evaluation periods.
     * @return EvaluationPeriod[]
     */
    public function findOpen(?string $type = null): array
    {
        $qb = $this->createQueryBuilder('ep')
            ->where('ep.status = true')
            ->andWhere('ep.startDate <= :now')
            ->andWhere('ep.endDate >= :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('ep.startDate', 'DESC');

        if ($type) {
            $qb->andWhere('ep.evaluationType = :type')->setParameter('type', $type);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find the latest open evaluation period for a given type.
     */
    public function findLatestOpen(string $type): ?EvaluationPeriod
    {
        return $this->createQueryBuilder('ep')
            ->where('ep.status = true')
            ->andWhere('ep.startDate <= :now')
            ->andWhere('ep.endDate >= :now')
            ->andWhere('ep.evaluationType = :type')
            ->setParameter('now', new \DateTime())
            ->setParameter('type', $type)
            ->orderBy('ep.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return EvaluationPeriod[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('ep')
            ->orderBy('ep.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find evaluation periods relevant to a specific faculty member.
     * Only returns SET/SEF periods matching the faculty's department (or unscoped).
     *
     * @return EvaluationPeriod[]
     */
    public function findForFaculty(?int $departmentId): array
    {
        $qb = $this->createQueryBuilder('ep')
            ->where('ep.evaluationType IN (:types)')
            ->setParameter('types', ['SET', 'SEF'])
            ->orderBy('ep.startDate', 'DESC');

        if ($departmentId) {
            $qb->andWhere('ep.department IS NULL OR ep.department = :dept')
               ->setParameter('dept', $departmentId);
        }

        return $qb->getQuery()->getResult();
    }
}
