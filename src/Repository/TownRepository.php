<?php

namespace App\Repository;

use App\Entity\Town;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Town>
 */
class TownRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Town::class);
    }
    
    public function findByQuery($query)
{
    return $this->createQueryBuilder('t')
        ->where('t.zipCode LIKE :query')
        ->setParameter('query', $query . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
}
    
}
