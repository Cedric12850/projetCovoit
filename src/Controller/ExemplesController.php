<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\CarUserRepository;
use App\Repository\ReservationRepository;
use App\Repository\TownRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExemplesController extends AbstractController
{
    #[Route('/exemples', name: 'app_exemples')]
    public function index(UserRepository $usrRep,
                          TripRepository $tripRep,
                          ReservationRepository $resaRep,
                          CarUserRepository $cuRep,
                          TownRepository $townRep,
                          EntityManagerInterface $emi,
                          ): Response
    {
        $nbAnAvant= 8 ;
        $nbUserAvant = 45470;
        $nbTripAvant = 3540;
        $nbPlaceAvant = 9500;
        $nbResaAvant = 7860;
        
        $nbUsers = $usrRep->count();
        $nbActiveUsers =  $usrRep->count(['active' =>True]);
        $nbCreesTrips = $tripRep->count(['cancel' =>false]);

        $nbTripCours= $tripRep->findTripsEnCours();

        $idConduct = $usrRep->findOneBy(['active'=>true]);
        $nbTripCoursByUser= $tripRep->findTripsEnCoursByUserId($idConduct);
        $nbAllTripByUser= $tripRep->findAllTripsByUserId($idConduct);

        // Date du jour à 00:00:01
        $dateRef = new DateTime();
        $nbJours = 3;
        $nbMois = 1;
        $nbAns = 0;
        $signe1 = "+";

        $txtModify= $signe1 . $nbJours . " day";
        $dateCalc = new \DateTime();
        $dateCalc = $dateRef;
        $dateCalc->modify($txtModify);
        if ($nbMois > 0){
            //$dateCalc->add($dateCalc("P{$nbMois}M"));         // P1M = Period of 1 Mois 
            $txtModify=  $nbMois . " month";
            $dateCalc->modify($txtModify);
        }
        if ($nbAns > 0){
            //$dateCalc->add($dateCalc("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify=  $nbAns . " year";
            $dateCalc->modify($txtModify);
        }
        $nbTripsPeriode = $tripRep->findTripsPeriode($dateRef,$nbJours,$nbMois,$nbAns,$signe1);

        $dateRef2 = new DateTime();
        $dateCalc2 = new \DateTime();
        $dateCalc2 = $dateRef2;
        $signe2 = "-";
        $txtModify= $signe2 . $nbJours . " day";
        $dateCalc->modify($txtModify);
        if ($nbMois > 0){
            //$dateCalc->add($dateCalc("P{$nbMois}M"));         // P1M = Period of 1 Mois 
            $txtModify= $signe2 . $nbMois . " month";
            $dateCalc2->modify($txtModify);
        }
        if ($nbAns > 0){
            //$dateCalc->add($dateCalc("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify= $signe2 . $nbAns . " year";
            $dateCalc2->modify($txtModify);
        }
        $nbTripsPeriodeConduct = $tripRep->findTripsPeriodeByUserId($idConduct,$dateRef2,$nbJours,$nbMois,$nbAns,$signe2);

        // Info toutes Résas
        $data = $resaRep->findNbTripResaAll("", $emi);
        $nbTripTot = $data['NbTrip'];
        $nbPlacesTripTot = $data['NbPlTrip'];
        $nbResaTot = $data['NbResa'];
        $nbPlacesTot = $data['NbPlResa'];

        $data = $resaRep->findNbTripResaAll(true, $emi);
        $nbPlacesTotAccept = $data['NbPlResa'];

        // Infos Résas en Cours
        $dateDeb = new \DateTime();
        $data = $resaRep->findNbTripResaByDates($dateDeb, "", "", $emi);
        $nbTripEnCours = $data['NbTrip'];
        $nbPlacesTripEnCours = $data['NbPlTrip'];
        $nbResaEnCours = $data['NbResa'];
        $nbPlacesEnCours = $data['NbPlResa'];

        //$data = $resaRep->findNbTripResaByDates($dateDeb, "", true, $emi);
        $nbPlacesAcceptEnCours = $data['NbPlResa'];

        // Les drivers autorisés
        $sql = "SELECT car_id , count(*) Nb FROM car_user  GROUP BY car_id ORDER BY Nb DESC LIMIT 1";
        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data = $result->fetchAssociative();

        $car=$data['car_id'];
        $drivers = $cuRep->findActiveDriversByIdCar($car);

        // tous les trajets 
        $allTrips = $tripRep->findAll();
        $allTripsRepo = $tripRep->findAllTripsEtSteps($emi);

        $ville1 = $townRep->findOneBy(['id' => 13171]) ;
        $ville2 = $townRep->findOneBy(['id' => 4534]) ;
        $detail = true;
        $detailTrips=[];
        $dispos=$tripRep->findDispoTrajet($ville1->getId(),$ville2->getId(),"","","",$detail, $emi);
    
        $totalTrips = 0;
        $totalTrips = $dispos['totalTrips'];
        $tripsByDate = $dispos['tripsByDate'];

        if ($detail){
            $detailTrips = $dispos['detailTrips'];
        }
        
        $dateJour = new DateTime();
        return $this->render('exemples/index.html.twig', [
            'nbAnAvant' => $nbAnAvant,
            'nbUserAvant' => $nbUserAvant,
            'nbTripAvant' => $nbTripAvant,
            'nbPlaceAvant' => $nbPlaceAvant,
            'nbResaAvant' => $nbResaAvant,

            'nbUsers' => $nbUserAvant + $nbUsers,
            'nbActiveUsers' => $nbUserAvant + $nbActiveUsers,

            'nbCreesTrips' => $nbTripAvant + $nbCreesTrips,
            'nbTripCours' =>  $nbTripCours,
            'nbPlaceAvant' =>  $nbPlaceAvant,

            'nbCreesResa' => $nbResaAvant ,
            'nbResaCours' => 0 ,

            'nbTripTot' => $nbTripTot,
            'nbPlacesTripTot' => $nbPlacesTripTot,
            'nbResaTot' => $nbResaTot,
            'nbPlacesTot' => $nbPlacesTot,

            'nbTripEnCours' => $nbTripEnCours ,
            'nbPlacesTripEnCours' => $nbPlacesTripEnCours,
            'nbResaEnCours' => $nbResaEnCours ,
            'nbPlacesEnCours' => $nbPlacesEnCours ,

            'nbPlacesTotAccept' => $nbPlacesTotAccept,
            'nbPlacesAcceptEnCours' => $nbPlacesAcceptEnCours,
            
            'drivers' => $drivers,
            'idUser' => $idConduct,
            'nbTripCoursByUser' => $nbTripCoursByUser,
            'nbAllTripByUser' => $nbAllTripByUser,

            // Période 
            'dateRef' => $dateJour->format('d-m-Y'),
            'dateCalc' => $dateCalc->format('d-m-Y'),
            'nbJMY' => $nbJours . "/" . $nbMois . "/" . $nbAns,
            'signe1' => $signe1,
            'nbTripsPeriode' => $nbTripsPeriode,

            // Période Conduct
            'dateRef2' => $dateJour->format('d-m-Y'),
            'dateCalc2' => $dateCalc2->format('d-m-Y'),
            'signe2' => $signe2,
            'nbTripsPeriodeConduct' => $nbTripsPeriodeConduct,

            // Tous les trajets 
            'allTrips' => $allTrips,
            'allTripsRepo' => $allTripsRepo,

            // Recherche Trajets
            'ville1' => $ville1,
            'ville2' => $ville2,
            'totalTrips' => $totalTrips,
            'tripsByDate' => $tripsByDate,
            'detailTrips' => $detailTrips
        ]);
    }
}
<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\CarUserRepository;
use App\Repository\ReservationRepository;
use App\Repository\TownRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExemplesController extends AbstractController
{
    #[Route('/exemples', name: 'app_exemples')]
    public function index(UserRepository $usrRep,
                          TripRepository $tripRep,
                          ReservationRepository $resaRep,
                          CarUserRepository $cuRep,
                          TownRepository $townRep,
                          EntityManagerInterface $emi,
                          ): Response
    {
        $nbAnAvant= 8 ;
        $nbUserAvant = 45470;
        $nbTripAvant = 3540;
        $nbPlaceAvant = 9500;
        $nbResaAvant = 7860;
        
        $nbUsers = $usrRep->count();
        $nbActiveUsers =  $usrRep->count(['active' =>True]);
        $nbCreesTrips = $tripRep->count(['cancel' =>false]);

        $nbTripCours= $tripRep->findTripsEnCours();

        $idConduct = $usrRep->findOneBy(['active'=>true]);
        $nbTripCoursByUser= $tripRep->findTripsEnCoursByUserId($idConduct);
        $nbAllTripByUser= $tripRep->findAllTripsByUserId($idConduct);

        // Date du jour à 00:00:01
        $dateRef = new DateTime();
        $nbJours = 3;
        $nbMois = 1;
        $nbAns = 0;
        $signe1 = "+";

        $txtModify= $signe1 . $nbJours . " day";
        $dateCalc = new \DateTime();
        $dateCalc = $dateRef;
        $dateCalc->modify($txtModify);
        if ($nbMois > 0){
            //$dateCalc->add($dateCalc("P{$nbMois}M"));         // P1M = Period of 1 Mois 
            $txtModify=  $nbMois . " month";
            $dateCalc->modify($txtModify);
        }
        if ($nbAns > 0){
            //$dateCalc->add($dateCalc("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify=  $nbAns . " year";
            $dateCalc->modify($txtModify);
        }
        $nbTripsPeriode = $tripRep->findTripsPeriode($dateRef,$nbJours,$nbMois,$nbAns,$signe1);

        $dateRef2 = new DateTime();
        $dateCalc2 = new \DateTime();
        $dateCalc2 = $dateRef2;
        $signe2 = "-";
        $txtModify= $signe2 . $nbJours . " day";
        $dateCalc->modify($txtModify);
        if ($nbMois > 0){
            //$dateCalc->add($dateCalc("P{$nbMois}M"));         // P1M = Period of 1 Mois 
            $txtModify= $signe2 . $nbMois . " month";
            $dateCalc2->modify($txtModify);
        }
        if ($nbAns > 0){
            //$dateCalc->add($dateCalc("P{$nbAn}Y"));           // P1Y = Period of 1 An
            $txtModify= $signe2 . $nbAns . " year";
            $dateCalc2->modify($txtModify);
        }
        $nbTripsPeriodeConduct = $tripRep->findTripsPeriodeByUserId($idConduct,$dateRef2,$nbJours,$nbMois,$nbAns,$signe2);

        // Info toutes Résas
        $data = $resaRep->findNbTripResaAll("", $emi);
        $nbTripTot = $data['NbTrip'];
        $nbPlacesTripTot = $data['NbPlTrip'];
        $nbResaTot = $data['NbResa'];
        $nbPlacesTot = $data['NbPlResa'];

        $data = $resaRep->findNbTripResaAll(true, $emi);
        $nbPlacesTotAccept = $data['NbPlResa'];

        // Infos Résas en Cours
        $dateDeb = new \DateTime();
        $data = $resaRep->findNbTripResaByDates($dateDeb, "", "", $emi);
        $nbTripEnCours = $data['NbTrip'];
        $nbPlacesTripEnCours = $data['NbPlTrip'];
        $nbResaEnCours = $data['NbResa'];
        $nbPlacesEnCours = $data['NbPlResa'];

        //$data = $resaRep->findNbTripResaByDates($dateDeb, "", true, $emi);
        $nbPlacesAcceptEnCours = $data['NbPlResa'];

        // Les drivers autorisés
        $sql = "SELECT car_id , count(*) Nb FROM car_user  GROUP BY car_id ORDER BY Nb DESC LIMIT 1";
        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $data = $result->fetchAssociative();

        $car=$data['car_id'];
        $drivers = $cuRep->findActiveDriversByIdCar($car);

        // tous les trajets 
        $allTrips = $tripRep->findAll();
        $allTripsRepo = $tripRep->findAllTripsEtSteps($emi);

        $ville1 = $townRep->findOneBy(['id' => 13171]) ;
        $ville2 = $townRep->findOneBy(['id' => 4534]) ;
        $detail = true;
        $detailTrips=[];
        $dispos=$tripRep->findDispoTrajet($ville1->getId(),$ville2->getId(),"","","",$detail, $emi);
    
        $totalTrips = 0;
        $totalTrips = $dispos['totalTrips'];
        $tripsByDate = $dispos['tripsByDate'];

        if ($detail){
            $detailTrips = $dispos['detailTrips'];
        }
        
        $dateJour = new DateTime();
        return $this->render('exemples/index.html.twig', [
            'nbAnAvant' => $nbAnAvant,
            'nbUserAvant' => $nbUserAvant,
            'nbTripAvant' => $nbTripAvant,
            'nbPlaceAvant' => $nbPlaceAvant,
            'nbResaAvant' => $nbResaAvant,

            'nbUsers' => $nbUserAvant + $nbUsers,
            'nbActiveUsers' => $nbUserAvant + $nbActiveUsers,

            'nbCreesTrips' => $nbTripAvant + $nbCreesTrips,
            'nbTripCours' =>  $nbTripCours,
            'nbPlaceAvant' =>  $nbPlaceAvant,

            'nbCreesResa' => $nbResaAvant ,
            'nbResaCours' => 0 ,

            'nbTripTot' => $nbTripTot,
            'nbPlacesTripTot' => $nbPlacesTripTot,
            'nbResaTot' => $nbResaTot,
            'nbPlacesTot' => $nbPlacesTot,

            'nbTripEnCours' => $nbTripEnCours ,
            'nbPlacesTripEnCours' => $nbPlacesTripEnCours,
            'nbResaEnCours' => $nbResaEnCours ,
            'nbPlacesEnCours' => $nbPlacesEnCours ,

            'nbPlacesTotAccept' => $nbPlacesTotAccept,
            'nbPlacesAcceptEnCours' => $nbPlacesAcceptEnCours,
            
            'drivers' => $drivers,
            'idUser' => $idConduct,
            'nbTripCoursByUser' => $nbTripCoursByUser,
            'nbAllTripByUser' => $nbAllTripByUser,

            // Période 
            'dateRef' => $dateJour->format('d-m-Y'),
            'dateCalc' => $dateCalc->format('d-m-Y'),
            'nbJMY' => $nbJours . "/" . $nbMois . "/" . $nbAns,
            'signe1' => $signe1,
            'nbTripsPeriode' => $nbTripsPeriode,

            // Période Conduct
            'dateRef2' => $dateJour->format('d-m-Y'),
            'dateCalc2' => $dateCalc2->format('d-m-Y'),
            'signe2' => $signe2,
            'nbTripsPeriodeConduct' => $nbTripsPeriodeConduct,

            // Tous les trajets 
            'allTrips' => $allTrips,
            'allTripsRepo' => $allTripsRepo,

            // Recherche Trajets
            'ville1' => $ville1,
            'ville2' => $ville2,
            'totalTrips' => $totalTrips,
            'tripsByDate' => $tripsByDate,
            'detailTrips' => $detailTrips
        ]);
    }
}
