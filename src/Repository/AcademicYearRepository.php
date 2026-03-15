<?php

namespace App\Repository;

use App\Entity\AcademicYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AcademicYear>
 */
class AcademicYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicYear::class);
    }

    /**
     * Get the currently active academic year.
     */
    public function findCurrent(): ?AcademicYear
    {
        return $this->findOneBy(['isCurrent' => true]);
    }

    /**
     * @return AcademicYear[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('ay')
            ->orderBy('ay.yearLabel', 'DESC')
            ->addOrderBy('ay.semester', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Clear any existing "current" flags.
     */
    public function clearCurrent(): void
    {
        $this->createQueryBuilder('ay')
            ->update()
            ->set('ay.isCurrent', ':false')
            ->setParameter('false', false)
            ->getQuery()
            ->execute();
    }
}
