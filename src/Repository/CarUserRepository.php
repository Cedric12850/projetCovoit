<?php

namespace App\Repository;

use App\Entity\CarUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarUser>
 */
class CarUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarUser::class);
    }


    public function findActiveDriversByIdCar($carId) : array
    {
        // Impossible carUser contient une relation vers car et 
        // car.id n'est pas accessible directement 
        // $drivers=$this->findBy(['carId' => $carId,
        //                          'active' =>True]);

        // Mais il faudrait aussi contrôler que User.active est True
        // Rq  : phpMyAdmin
        // SELECT C.driver_id, U.pseudo
        // FROM car_user C
        // INNER JOIN user U ON U.id = C.driver_id
        // WHERE C.car_id = $carId
        // AND U.active AND C.active
        // ORDER BY U.pseudo

        // Syntaxe DQL
        $drivers= $this->createQueryBuilder('C')    // C = car_user - U = user
                       ->join('C.driver', 'U')     // Jointure avec la table User
                       ->where('C.car = :carId')
                       ->andWhere('C.active = :active')
                       ->andWhere('U.active = :active')
                       ->setParameter('carId', $carId)
                       ->setParameter('active', true)
                       ->orderBy('U.pseudo', 'ASC')
                       ->getQuery()
                       ->getResult();
        Return $drivers;
    }

    //    /**
    //     * @return CarUser[] Returns an array of CarUser objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CarUser
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
