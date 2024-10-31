<?php

namespace App\Controller;

use App\Repository\CarUserRepository;
use App\Repository\ReservationRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $usrRep,
                          TripRepository $tripRep,
                          EntityManagerInterface $emi,
                          CarUserRepository $cuRep,
                          ReservationRepository $resaRep
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

        $idConduct = 2;
        $nbTripCoursByUser= $tripRep->findTripsEnCoursByUserId($idConduct);
        $nbAllTripByUser= $tripRep->findAllTripsByUserId($idConduct);

        // Date du jour à 00:00:01
        $dateRef = new DateTime();
        $nbJours = 3;
        $nbMois = 1;
        $nbAns = 0;
        $signe1 = "+";
        $nbTripsPeriode = $tripRep->findTripsPeriode($dateRef,$nbJours,$nbMois,$nbAns,$signe1);

        $dateRef2 = new DateTime();
        $dateRef2->modify('+1 month');
        $signe2 = "-";
        $nbTripsPeriodeConduct = $tripRep->findTripsPeriodeByUserId($idConduct,$dateRef2,$nbJours,$nbMois,$nbAns,$signe2);
        //dd($nbTripsPeriodeConduct);

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

        // Test  Des drivers autorisés
        $drivers = $cuRep->findActiveDriversByIdCar(11);
        //dd($drivers);


        $dispo=$tripRep->findDispoTrajet(13171,4534,"Cumul", $emi);
        //dd( $dispo);


        return $this->render('home/index.html.twig', [
            'nbAnnees' => $nbAnAvant,
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
            'dateRef' => $dateRef->format('d-m-Y'),
            'nbJMY' => $nbJours . "/" . $nbMois . "/" . $nbAns,
            'signe1' => $signe1,
            'nbTripsPeriode' => $nbTripsPeriode,

             // Période Conduct
             'dateRef2' => $dateRef2->format('d-m-Y'),
            'signe2' => $signe2,
            'nbTripsPeriodeConduct' => $nbTripsPeriodeConduct
        ]);
    }
}
