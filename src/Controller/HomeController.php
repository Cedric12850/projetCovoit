<?php

namespace App\Controller;

use App\Repository\CarUserRepository;
use App\Repository\ReservationRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $usrRep,
                          TripRepository $tripRep,
                          ): Response
    {
        $user = $this->getUser();

        $nbAnAvant= 8 ;
        $nbUserAvant = 45470;
        $nbTripAvant = 3540;

        $nbUsers = $usrRep->count();
        $nbActiveUsers =  $usrRep->count(['active' =>True]);
        $nbCreesTrips = $tripRep->count(['cancel' =>false]);
        
        $nbTripCours= $tripRep->findTripsEnCours();

        return $this->render('home/index.html.twig', [
            'nbAnnees' => $nbAnAvant,
            'nbUsers' => $nbUserAvant + $nbUsers,
            'nbActiveUsers' => $nbUserAvant + $nbActiveUsers,

            'nbCreesTrips' => $nbTripAvant + $nbCreesTrips,
            'nbDispoTrips' => $nbTripAvant + $nbTripCours,
            'nbTripCours' => $nbTripCours,

            'user' => $user,
        ]);
    }

    /* ------------- Recherche trajets dans Home ---------------------------- */
    #[Route('/search', name: 'app_search')]
    public function searchTrips(Request $request, 
                                TripRepository $tripRep, 
                                EntityManagerInterface $emi): JsonResponse
    {
        $villeDep = $request->request->get('villeDep');
        $villeArr = $request->request->get('villeArr');
        $dispos = $tripRep->findDispoTrajet($villeDep, $villeArr, "", "", "", false, $emi);

        return new JsonResponse([
            'totalTrips' => $dispos['totalTrips'],
            'tripsByDate' => $dispos['tripsByDate'],
        ]);
    }
}
