<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findMostRecentUpdatedAt()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.updated_at')
            ->orderBy('p.updated_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($qb && $qb['updated_at']) {
            return $qb['updated_at'];
        }

        return $qb;
    }
}
