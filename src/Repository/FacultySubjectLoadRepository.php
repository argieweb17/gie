<?php

namespace App\Repository;

use App\Entity\FacultySubjectLoad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FacultySubjectLoad>
 */
class FacultySubjectLoadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacultySubjectLoad::class);
    }

    /**
     * @return FacultySubjectLoad[]
     */
    public function findByFacultyAndAcademicYear(int $facultyId, ?int $academicYearId): array
    {
        $qb = $this->createQueryBuilder('fsl')
            ->where('fsl.faculty = :fid')
            ->setParameter('fid', $facultyId);

        if ($academicYearId) {
            $qb->andWhere('fsl.academicYear = :ayId')
               ->setParameter('ayId', $academicYearId);
        }

        return $qb->getQuery()->getResult();
    }

    public function removeByFacultyAndAcademicYear(int $facultyId, ?int $academicYearId): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->delete(FacultySubjectLoad::class, 'fsl')
            ->where('fsl.faculty = :fid')
            ->setParameter('fid', $facultyId);

        if ($academicYearId) {
            $qb->andWhere('fsl.academicYear = :ayId')
               ->setParameter('ayId', $academicYearId);
        }

        return $qb->getQuery()->execute();
    }

    /**
     * Find all loads for a faculty from past (non-current) academic years, grouped by AY.
     * @return FacultySubjectLoad[]
     */
    public function findPastLoadsByFaculty(int $facultyId, ?int $currentAyId): array
    {
        $qb = $this->createQueryBuilder('fsl')
            ->join('fsl.academicYear', 'ay')
            ->where('fsl.faculty = :fid')
            ->setParameter('fid', $facultyId)
            ->orderBy('ay.yearLabel', 'DESC')
            ->addOrderBy('ay.semester', 'ASC');

        if ($currentAyId) {
            $qb->andWhere('fsl.academicYear != :currentId')
               ->setParameter('currentId', $currentAyId);
        }

        return $qb->getQuery()->getResult();
    }

    public function removeByFacultySubjectAndAcademicYear(int $facultyId, int $subjectId, int $academicYearId): int
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->delete(FacultySubjectLoad::class, 'fsl')
            ->where('fsl.faculty = :fid')
            ->andWhere('fsl.subject = :sid')
            ->andWhere('fsl.academicYear = :ayId')
            ->setParameter('fid', $facultyId)
            ->setParameter('sid', $subjectId)
            ->setParameter('ayId', $academicYearId)
            ->getQuery()
            ->execute();
    }
}
