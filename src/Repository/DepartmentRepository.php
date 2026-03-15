<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    /**
     * @return Department[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.collegeName', 'ASC')
            ->addOrderBy('d.departmentName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return string[] Distinct non-null college names, sorted alphabetically
     */
    public function findDistinctCollegeNames(): array
    {
        return array_column(
            $this->createQueryBuilder('d')
                ->select('DISTINCT d.collegeName')
                ->where('d.collegeName IS NOT NULL')
                ->orderBy('d.collegeName', 'ASC')
                ->getQuery()
                ->getScalarResult(),
            'collegeName'
        );
    }

    /**
     * Returns a map of department ID => college name for JS filtering.
     * @return array<int, string|null>
     */
    public function getDepartmentCollegeMap(): array
    {
        $rows = $this->createQueryBuilder('d')
            ->select('d.id, d.collegeName')
            ->getQuery()
            ->getScalarResult();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['id']] = $row['collegeName'];
        }
        return $map;
    }
}
