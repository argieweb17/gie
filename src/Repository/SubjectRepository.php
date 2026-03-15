<?php

namespace App\Repository;

use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subject>
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    /**
     * @return Subject[]
     */
    public function findByFaculty(int $facultyId): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.faculty = :fid')
            ->setParameter('fid', $facultyId)
            ->orderBy('s.subjectCode', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Subject[] — all subjects in curricula belonging to the department
     */
    public function findByDepartment(int $departmentId): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.curricula', 'c')
            ->where('c.department = :did')
            ->setParameter('did', $departmentId)
            ->groupBy('s.id')
            ->orderBy('s.subjectCode', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return string[]
     */
    public function findDistinctSemesters(): array
    {
        return array_column(
            $this->createQueryBuilder('s')
                ->select('DISTINCT s.semester')
                ->where('s.semester IS NOT NULL')
                ->orderBy('s.semester', 'ASC')
                ->getQuery()
                ->getScalarResult(),
            'semester'
        );
    }

    /**
     * @return string[]
     */
    public function findDistinctYearLevels(): array
    {
        return array_column(
            $this->createQueryBuilder('s')
                ->select('DISTINCT s.yearLevel')
                ->where('s.yearLevel IS NOT NULL')
                ->orderBy('s.yearLevel', 'ASC')
                ->getQuery()
                ->getScalarResult(),
            'yearLevel'
        );
    }

    /**
     * @return Subject[]
     */
    public function findBySemesterAndYear(?string $semester, ?string $schoolYear): array
    {
        $qb = $this->createQueryBuilder('s');
        if ($semester) {
            $qb->andWhere('s.semester = :sem')->setParameter('sem', $semester);
        }
        if ($schoolYear) {
            $qb->andWhere('s.schoolYear = :sy')->setParameter('sy', $schoolYear);
        }
        return $qb->orderBy('s.subjectCode', 'ASC')->getQuery()->getResult();
    }
}
