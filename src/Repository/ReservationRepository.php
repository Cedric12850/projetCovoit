<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }


    /**
     * Trouve le nombre total de voyages et de réservations avec leurs places respectives.
     *
     * @param bool|string $accept Filtre les réservations acceptées. 
     *                            true pour les réservations acceptées, 
     *                            false pour les non acceptées ou nulles, 
     *                            "" (chaîne vide) pour toutes les réservations.
     * @param EntityManagerInterface $emi L'entity manager de Doctrine.
     *
     * @return array Un tableau associatif contenant :
     *               - 'NbTrip' : Le nombre total de voyages
     *               - 'NbPlTrip' : Le nombre total de places pour tous les voyages
     *               - 'NbResa' : Le nombre total de réservations
     *               - 'NbPlResa' : Le nombre total de places réservées
     *
     * @throws \Doctrine\DBAL\Exception Si une erreur de base de données se produit.
     */
    public function findNbTripResaAll($accept, $emi): array
    {
        // Nombre TOTAL de réservations et cumul des places
        $sql = "SELECT count(*) AS NbTrip, sum(NbPlaces) as NbPlTrip, count(NbPlacesResa) AS NbResa, sum(NbPlacesResa) AS NbPlResa
                FROM (SELECT T.id, T.nb_passenger NbPlaces, MAX(R.nb_place) NbPlacesResa
                      FROM trip T 
                      LEFT JOIN step S ON S.trip_id = T.id
                      LEFT JOIN reservation R ON R.step_id = S.id ";

        // Tri sur le flag Accepté de Réservation
        if ($accept <> "") {
            if ($accept) {
                $sql =  $sql . "WHERE R.accept " ;
            }else{
                $sql =  $sql . "WHERE NOT R.accept OR R.accept IS NULL " ;
            }
        }

        $sql =  $sql . " GROUP BY T.id , NbPlaces) AS reserv" ;

        // if ($accept <> "") {
        //     dd($sql);
        // }

        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data = $result->fetchAssociative();
        return $data;
    }


    // public function findNbTripResaByDates($dateDeb,
    //         $dateFin,
    //         $emi): array
    // {
    public function findNbTripResaByDates($dateDeb,
                                          $dateFin,
                                          $accept,
                                          $emi): array
    {
        $debut = new \DateTime();
        $debut = $dateDeb->format('Y-m-d');
        // Nombre TOTAL de réservations et cumul des places
        $sql = "SELECT count(*) AS NbTrip, sum(NbPlaces) as NbPlTrip, count(NbPlacesResa) AS NbResa, sum(NbPlacesResa) AS NbPlResa
                FROM (SELECT T.id, T.nb_passenger NbPlaces, MAX(R.nb_place) NbPlacesResa
                      FROM trip T 
                      LEFT JOIN step S ON S.trip_id = T.id
                      LEFT JOIN reservation R ON R.step_id = S.id
                      WHERE T.date_start >= '" . $debut . "' ";

        if ($dateFin <> "" AND $dateDeb <= $dateFin) {
            $fin = new \DateTime();
            $fin = $dateFin->format('Y-m-d') ;
            $sql =  $sql . "AND T.date_start <= '" . $fin . "' " ;
        }
        
        // Tri sur le flag Accepté de Réservation              
        if ($accept <> "") {
            if ($accept) {
                $sql =  $sql . "WHERE R.accept " ;
            }else{
                $sql =  $sql . "WHERE NOT R.accept OR R.accept IS NULL " ;
            }
        }
        $sql =  $sql . "GROUP BY T.id , NbPlaces) AS reserv";

        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data = $result->fetchAssociative();
        return $data;
    }
    

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
