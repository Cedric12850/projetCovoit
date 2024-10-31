<?php

namespace App\Repository;

use App\Entity\Trip;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

     public function findAllTripsByUserId($idUser) : int
    {
        return $this->createQueryBuilder('T')
                    ->select('COUNT(T.id)')
                    ->where('T.cancel = :cancel')
                    ->andWhere('T.driver = :idUser')
                    ->setParameter('cancel', false)
                    ->setParameter('idUser', $idUser)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findTripsEnCoursByUserId($idUser) : int
    {
        $dateRef = new DateTime();
        $dateRef->add(new DateInterval('P1D'));     // P1D = Period of 1 Day + 1 => 
        $dateRef->setTime(0, 0, 1);                 //  Définir l'heure à 00:00:01
        // $dateRef = new \DateTime();             // Date du jour
        // $dateRef->modify('+1 day');             // Incrémente d'un jour
        $dateRef= $dateRef->format('Y-m-d');
        //dd($dateRef);

        return $this->createQueryBuilder('T')
                    ->select('COUNT(T.id)')
                    ->where('T.cancel = :cancel')
                    ->andWhere('T.date_start >= :dateRef')
                    ->andWhere('T.driver = :idUser')
                    ->setParameter('cancel', false)
                    ->setParameter('dateRef', $dateRef)
                    ->setParameter('idUser', $idUser)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findTripsEnCours() : int
    {
        // $dateRef = new DateTime();
        // $dateRef->add(new DateInterval('P1D'));     // P1D = Period of 1 Day + 1 => 
        // $dateRef->setTime(0, 0, 1);                 //  Définir l'heure à 00:00:01
        
        $dateRef = new \DateTime();             // Date du jour
        $dateRef->modify('+1 day');             // Incrémente d'un jour
        $dateRef= $dateRef->format('Y-m-d');
        // dd($dateRef);

        return $this->createQueryBuilder('T')
                    ->select('COUNT(T.id)')
                    ->where('T.cancel = :cancel')
                    ->andWhere('T.date_start >= :dateRef')
                    ->setParameter('cancel', false)
                    ->setParameter('dateRef', $dateRef)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findTripsPeriode($dateRef, $nbJours, $nbMois, $nbAn, $signe) : int
    {
        $signe= $signe=="+"? "+" : "-";  
        $dateDeb = new \DateTime();
        $dateDeb = $dateRef;
        $dateFin = new \DateTime();
        $dateFin = $dateRef;

        $nbJours= $nbJours + 1;                             // car dans la rq,  t.date_start < :dateFin
        //$dateFin->add($dateFin("P{$nbJours}D"));            // P1D = Period of 1 Jour
        //$dateFin->modify('+1 day');
        //$txtModify= "'" . $signe . $nbJours . " day'";
        $txtModify= $signe . $nbJours . " day";
        //dd($txtModify);
        $dateFin->modify($txtModify);
        if ($nbMois > 0){
            //$dateFin->add($dateFin("P{$nbMois}M"));         // P1M = Period of 1 Mois 
            $txtModify= $signe . $nbJours . " month";
            $dateFin->modify($txtModify);
        }
        if ($nbAn > 0){
            //$dateFin->add($dateFin("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify= $signe . $nbJours . " year";
            $dateFin->modify($txtModify);
        }

        // inversion de la période si le signe est différent de +
        if ($signe <> "+"){             
            $dateDeb = $dateFin;
            $dateFin = $dateRef;
        }

        // $dateDeb= $dateDeb->format('Y-m-d');
        // $dateFin= $dateFin->format('Y-m-d');

        return $this->createQueryBuilder('T')
                    ->select('COUNT(T.id)')
                    ->where('T.cancel = :cancel')
                    ->andWhere('T.date_start >= :dateDeb')
                    ->andWhere('T.date_start < :dateFin')
                    ->setParameter('cancel', false)
                    ->setParameter('dateDeb', $dateDeb)
                    ->setParameter('dateFin', $dateFin)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findTripsPeriodeByUserId($idUser, $dateRef, $nbJours, $nbMois, $nbAn, $signe) : int
    {
        $signe= $signe=="+"? "+" : "-";
        // $dateRef->setTime(0, 0, 1);                         // forcer l'heure à 00:00:01   
        // $dateDeb = new DateTime($dateRef);               
        // $dateFin = new DateTime($dateRef); 
        $dateDeb = new \DateTime();
        $dateDeb = $dateRef;
        $dateFin = new \DateTime();
        $dateFin = $dateRef;
        
        $nbJours= $nbJours + 1;                             // car dans la rq,  t.date_start < :dateFin
        $txtModify= $signe . $nbJours . " day";
        //dd($txtModify);
       // $dateFin->add($dateFin("P{$nbJours}D"));            // P1D = Period of 1 Jour
        $dateFin->modify($txtModify);
        if ($nbMois > 0){
            //$dateFin->add($dateFin("P{$nbMois}M"));         // P1M = Period of 1 Mois 
            $txtModify= $signe . $nbJours . " month";
            $dateFin->modify($txtModify);
        }
        if ($nbAn > 0){
            //$dateFin->add($dateFin("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify= $signe . $nbJours . " year";
            $dateFin->modify($txtModify);
        }
        
        // inversion de la période si le signe est différent de +
        if ($signe <> "+"){             
            $dateDeb = $dateFin;
            $dateFin = $dateRef;
        }

        return $this->createQueryBuilder('T')
                    ->select('COUNT(T.id)')
                    ->where('T.cancel = :cancel')
                    ->andWhere('T.date_start >= :dateDeb')
                    ->andWhere('T.date_start < :dateFin')
                    ->andWhere('T.driver = :idUser')
                    ->setParameter('cancel', false)
                    ->setParameter('dateDeb', $dateDeb)
                    ->setParameter('dateFin', $dateFin)
                    ->setParameter('idUser', $idUser)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findDispoTrajet($idDepart, $idArrivee,$detailOuCumuls, $emi): array
    {
        $debut = new \DateTime();
        $debut = $debut->format('Y-m-d');
        // Sélection des Trip qui ont la ville de départ
        $sql = "SELECT T.id , 1 num_order, T.date_start  FROM trip T  WHERE T.date_start >= '" . $debut . "' AND T.town_start_id = " . $idDepart ;
        $sql = $sql . " UNION SELECT T.id , S.num_order + 1 , T.date_start FROM step S INNER JOIN trip T ON T.id = S.trip_id WHERE T.date_start >= '" . $debut . "' AND S.town_step_id = " . $idDepart ;

        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data1 = $result->fetchAllAssociative();
       // dump($data1);


        // $sql = "SELECT T.id , S.num_order , T.date_start FROM step S INNER JOIN trip T ON T.id = S.trip_id WHERE T.date_start >= '" . $debut . "' AND S.town_step_id = " . $idDepart ;
        // $stmt = $emi->getConnection()->prepare($sql);
        // $result = $stmt->executeQuery();
        // $data1 = $result->fetchAssociative();
 
        // Sélection des Trip qui ont la ville d'arrivée
        $sql = "SELECT S.trip_id , S.num_order
                FROM step S
                INNER JOIN trip T ON S.trip_id = T.id
                WHERE S.town_step_id = " . $idArrivee  . 
                " AND T.date_start >= '" . $debut . 
                "' ORDER BY T.date_start ASC ";
        //dd($sql);
        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data2 = $result->fetchAllAssociative();
        //dd($data2);
        
        if (!$data2 OR !$data1) { 
            $filteredData= ['Aucun résultat'];
        } else { 
            // Comparaison des résultats pour ne retenir que les Trip communs
            // et dont NumOrdre Départ < à NumOrcre arrivée
            $filteredData = $this->compareData($data1, $data2,$detailOuCumuls);
        }
        

        return  $filteredData ;
    }

    public function compareData(array $data1, array $data2, $detailOuCumuls): array
    {
        $result = [];
        $totalTrips = 0;
        $tripsByDate = [];          // Détail par date
        $processedTrips = [];       // Id des trip retenus

        if ($detailOuCumuls=="detail") {            // Envoyer détail
            foreach ($data2 as $item2) {
                foreach ($data1 as $item1) {
                    if ($item1['id'] === $item2['id'] && $item1['NumOrdre'] < $item2['NumOrdre']) {
                        $result[] = [
                            'id' => $item1['id'],
                            'depart' => $item1['date_star'],
                            'Etape1' => $item1['Etape'],
                            'NumOrdre1' => $item1['NumOrdre'],
                            'Etape2' => $item2['Etape'],
                            'NumOrdre2' => $item2['NumOrdre']
                        ];
                        break; // Sortir de la boucle interne une fois qu'une correspondance est trouvée
                    }
                }
            }
        } else {

            // Envoyer le total des trajets, et le détail par date
            // Ne pas prendre en compte 2 fois le même trajet
            foreach ($data2 as $item2) {
                foreach ($data1 as $item1) {
                    // Vérifier si ce trajet n'a pas déjà été traité
                    if (empty($processedTrips) OR !isset($processedTrips[$item2['trip_id']])) {

                        if ($item1['id']=== $item2['trip_id'] && $item1['num_order'] < $item2['num_order']) {
                            $totalTrips = $totalTrips+1;
                            $dateStart = $item1['date_start']->format('Y-m-d');
                            //dump ($item1['date_start']);
                            if (!isset($tripsByDate[$dateStart])) {
                                $tripsByDate[$dateStart] = 1;
                            } else {
                                $tripsByDate[$dateStart]++;
                            }
                            $processedTrips[$item2['trip_id']] = true;
                            dump ($totalTrips);
                        }
                        break; // Sortir de la boucle interne une fois qu'une correspondance est trouvée
                    }
                }
            }
            // Trier les dates de départ  => inutile car $data2 est trié par ordre croissant
            // ksort($tripsByDate);

            $result = [
                'total_trips' => $totalTrips,
                'trips_by_date' => $tripsByDate
            ];

            
            // dump(" ---------- Résultat -------");
            // dump($totalTrips);
            // dump($tripsByDate);
            // dump($result);

            // dd($processedTrips) ;

        }
        return $result;
    }


    //    /**
    //     * @return Trip[] Returns an array of Trip objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Trip
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
