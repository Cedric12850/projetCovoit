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

    public function findAllTripsEtSteps($emi) :array
    {
        $sql = "SELECT T.id, T.nb_passenger ,T.date_start As 'dateDep' , U.pseudo, C.brand, V2.name AS 'depart', count(S.id) AS 'NbSteps' ,
                GROUP_CONCAT(V.name ORDER BY S.num_order SEPARATOR ', ') AS 'etapes'
                FROM trip T
                INNER JOIN town V2 ON T.town_start_id = V2.id
                INNER JOIN user U ON U.id = T.driver_id
                INNER JOIN car C ON C.id = T.car_id
                LEFT JOIN step S ON T.id = S.trip_id
                LEFT JOIN town V ON S.town_step_id = V.id
                GROUP BY T.id, dateDep, depart , T.nb_passenger , U.pseudo, C.brand
                ORDER BY T.date_start";        
        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $allTripsSteps = $result->fetchAllAssociative();

        return $allTripsSteps;
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

        return $this->createQueryBuilder('T')
                    ->select('COUNT(T.id)')
                    ->where('T.cancel = :cancel')
                    ->andWhere('T.date_start >= :dateRef')
                    ->setParameter('cancel', false)
                    ->setParameter('dateRef', $dateRef)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findTripsPeriode($dateRef, $nbJours, $nbMois, $nbAns, $signe) : int
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
            $txtModify= $signe . $nbMois . " month";
            $dateFin->modify($txtModify);
        }
        if ($nbAns > 0){
            //$dateFin->add($dateFin("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify= $signe . $nbAns . " year";
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
                    ->setParameter('cancel', false)
                    ->setParameter('dateDeb', $dateDeb)
                    ->setParameter('dateFin', $dateFin)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findTripsPeriodeByUserId($idUser, $dateRef, $nbJours, $nbMois, $nbAns, $signe) : int
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
       // $dateFin->add($dateFin("P{$nbJours}D"));            // P1D = Period of 1 Jour
        $txtModify= $signe . $nbJours . " day";
        //dd($txtModify);
        $dateFin->modify($txtModify);
        if ($nbMois > 0){
            //$dateFin->add($dateFin("P{$nbMois}M"));         // P1M = Period of 1 Mois 
            $txtModify= $signe . $nbMois . " month";
            $dateFin->modify($txtModify);
        }
        if ($nbAns > 0){
            //$dateFin->add($dateFin("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify= $signe . $nbAns . " year";
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


    // public function findDispoTrajet($villeDepart, $villeArrivee,$detail, $emi): array
    // Ajout des paramètres $dateDebut, $dateFin, $nbPlace pour correspondre aux besoins du formulaire de recherche de Nicolas
    
    public function findDispoTrajet($villeDepart, $villeArrivee, $dateDebut, $dateFin, $nbPlace, $detail, $emi): array
    {
    // Ex de différents appels possibles :
    // Pour la page Exemples :       $results = findDispoTrajet(1, 2, "", "", 2, True, $emi)
    // Pour form recherche trajet :  $results = findDispoTrajet(1, 2,  07/11/2024, "",2, True, $emi)
   
        $idDepart = $villeDepart;
        $idArrivee=  $villeArrivee;
        $debut = $dateDebut == ""? new \DateTime() : new \DateTime($dateDebut) ;
        $fin = $dateFin == ""? new \DateTime($dateDebut) : new \DateTime($dateFin) ;
        $debut = $debut->format('Y-m-d');
        $fin = $fin->format('Y-m-d');
        // Sélection des Trip qui ont la ville de départ
        // Dans la table Trip :
        $sql = "SELECT T.id , 0 noOrder, T.date_start  
                FROM trip T  
                INNER JOIN user U ON U.id = T.driver_id
                WHERE T.date_start >= '" . $debut . "' AND T.town_start_id = " . $idDepart .
                      " AND U.active " ;
        if (!$dateDebut == "") {
            $sql = $sql . " AND T.date_start <= '" . $fin ."'" ;
        }
        if  ($nbPlace <> ""){
            $sql = $sql . " AND nb_passenger >= " . $nbPlace;
        }
        // Dans la table Step :
        $sql = $sql . " UNION SELECT T.id , S.num_order , T.date_start 
                        FROM step S 
                        INNER JOIN trip T ON T.id = S.trip_id   
                        INNER JOIN user U ON U.id = T.driver_id
                        WHERE  T.date_start >= '" . $debut . "' AND U.active AND S.town_step_id = " . $idDepart;
        if (!$dateDebut == "") {
            $sql = $sql . " AND T.date_start <= '" . $fin ."'" ;
        }
        if  ($nbPlace <> ""){
            $sql = $sql . " AND nb_passenger >= " . $nbPlace;
        }
        dump($sql);

        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data1 = $result->fetchAllAssociative();
 
        // Sélection des Trip qui ont la ville d'arrivée
        $sql = "SELECT S.trip_id , S.num_order as noOrder
                FROM step S
                INNER JOIN trip T ON S.trip_id = T.id  
                INNER JOIN user U ON U.id = T.driver_id
                WHERE S.town_step_id = " . $idArrivee  .  " AND T.date_start >= '" .$debut . "'" ;
        if (!$dateDebut == "") {
            $sql = $sql . " AND T.date_start <= '" . $fin ."'" ;
        }
        if  ($nbPlace <> ""){
            $sql = $sql . " AND nb_passenger >= " . $nbPlace;
        }
        $sql = $sql ." AND U.active ORDER BY T.date_start ASC " ;

        dump($sql);

        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data2 = $result->fetchAllAssociative();
        
        if (!$data2 OR !$data1) { 
            $filteredData= ['Aucun résultat'];
        } else { 
            // Comparaison des résultats pour ne retenir que les Trip communs
            // et dont NumOrdre Départ < à NumOrcre arrivée
            $filteredData = $this->compareData($data1, $data2,$detail);
        }

        return  $filteredData ;
    }

    public function compareData(array $data1, array $data2, $detail): array
    {
        $result = [];
        $totalTrips = 0;
        $tripsByDate = [];          // Détail par date
        $processedTrips = [];       // Id des trip retenus
        $detailTrips= [];

        // Envoyer le total des trajets, et le détail par date
        // Ne pas prendre en compte 2 fois le même trajet
        foreach ($data2 as $item2) {
            foreach ($data1 as $item1) {
                // Vérifier si ce trajet n'a pas déjà été traité
                if ($item1['id']=== $item2['trip_id'] && $item1['noOrder'] < $item2['noOrder']) {
                    // dump($item2);
                    // dd($item1);
                    if ($detail){
                        $detailTrips[] = [
                            'id' => $item1['id'],
                            'dateStart' => $item1['date_start'],
                            'etapeDep' => $item1['noOrder'],
                            'etapeArr' => $item2['noOrder']
                        ];
                    }

                    if (empty($processedTrips) OR !isset($processedTrips[$item2['trip_id']])) { 
                        $totalTrips = $totalTrips+1;
                        $dateStart = $item1['date_start'];
                        
                        if (!isset($tripsByDate[$dateStart])) {
                            $tripsByDate[$dateStart] = 1;
                        } else {
                            $tripsByDate[$dateStart]++;
                        }
                        $processedTrips[$item2['trip_id']] = true;
                    }
                }   
                // Trier les dates de départ  => inutile car $data2 est trié par ordre croissant
                // ksort($tripsByDate);
            }
        }
        dump($result);
        if ($detail){
            $result = [
                'totalTrips' => $totalTrips,
                'tripsByDate' => $tripsByDate,
                'detailTrips' => $detailTrips
            ];
        } else {
            $result = [
                'totalTrips' => $totalTrips,
                'tripsByDate' => $tripsByDate,
            ];
        }

        return $result;
    }
}
