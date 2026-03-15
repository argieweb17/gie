<?php

namespace App\Repository;

use App\Entity\Enrollment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enrollment>
 */
class EnrollmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enrollment::class);
    }

    /**
     * Get all subjects a student is enrolled in.
     * @return Enrollment[]
     */
    public function findByStudent(int $studentId): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.subject', 's')
            ->where('e.student = :sid')
            ->setParameter('sid', $studentId)
            ->orderBy('s.subjectCode', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all students enrolled in a subject.
     * @return Enrollment[]
     */
    public function findBySubject(int $subjectId): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.subject = :sid')
            ->setParameter('sid', $subjectId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if a student is enrolled in a specific subject.
     */
    public function isEnrolled(int $studentId, int $subjectId): bool
    {
        return (bool) $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.student = :sid')
            ->andWhere('e.subject = :subid')
            ->setParameter('sid', $studentId)
            ->setParameter('subid', $subjectId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
