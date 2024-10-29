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
    
    public function findByQuery($val):array
{
    return $this->createQueryBuilder('t')
        ->where('t.zip_code LIKE :val')
        ->setParameter('val', $val)
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
}
    
}
