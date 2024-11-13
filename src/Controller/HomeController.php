<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripSearchType;
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
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(UserRepository $usrRep,
                          TripRepository $tripRep,
                          Request $request,
                          ): Response
    {


                // Générer l'URL pour l'autocomplétion
                $autocompleteUrl = $this->generateUrl('api_towns');

                $form = $this->createForm(TripSearchType::class, null, [
                ]);
        
                $form->handleRequest($request);
                
                if ($form->isSubmitted() && $form->isValid()) {
                    
                    $data = $form->getData();
                    $townStart = $data['town_start'];
                    /* $townStartId = $data['town_start_id']; */
                    /* dump($townStartId); */
                    
                    $townEnd = $data['town_end'];
                    /* $townEndId = $data['town_end_id']; */
                    /* dd($townEndId); */
        

                        
                    return $this->render('tripsearch/
                    .twig', [
                        'town_start' => $townStart,
                        'town_end' => $townEnd,
                       
                    ]);
                }

        $user = $this->getUser();

        $nbAnAvant= 8 ;
        $nbUserAvant = 45470;
        $nbTripAvant = 3540;
        $nbResaAvant = 5000;
        $nbUsers = $usrRep->count();
        $nbActiveUsers =  $usrRep->count(['active' =>True]);
        $nbCreesTrips = $tripRep->count(['cancel' =>false]);
        
        $nbTripCours= $tripRep->findTripsEnCours();

        return $this->render('home/index.html.twig', [
            'nbAnnees' => $nbAnAvant,
            'nbUsers' => $nbUserAvant + $nbUsers,
            'nbResa' => $nbResaAvant,
            'nbActiveUsers' => $nbUserAvant + $nbActiveUsers,
            'nbResaAvant' => $nbResaAvant,

            'nbCreesTrips' => $nbTripAvant + $nbCreesTrips,
            'nbDispoTrips' => $nbTripAvant + $nbTripCours,
            'nbTripCours' => $nbTripCours,

            'user' => $user,
            'form' => $form->createView(),
        ]);

    }

    /* ------------- Recherche trajets dans Home ---------------------------- */
    #[Route('/search', name: 'app_search')]
    public function searchTrips(Request $request, 
                                TripRepository $tripRep, 
                                EntityManagerInterface $emi): JsonResponse
    {
        // Récupérer les IDs des villes à partir de la requête POST
        $villeDep = $request->request->get('trip_search_town_start_id');  // ID de la ville de départ
        $villeArr = $request->request->get('trip_search_town_end_id');    // ID de la ville d'arrivée
    
        // Vérification des données
        if (!$villeDep || !$villeArr) {
            return new JsonResponse([
                'error' => 'Les IDs des villes de départ et d\'arrivée sont requis.',
            ], 400);  // Retourner une erreur si les données sont manquantes
        }
    
        // Appel à la méthode du repository pour récupérer les trajets disponibles
        $dispos = $tripRep->findDispoTrajet($villeDep, $villeArr, "", "", "", false, $emi);
    
        // Retourner les résultats sous forme de JSON
        return new JsonResponse([
            'totalTrips' => $dispos['totalTrips'],  // Nombre total de trajets
            'tripsByDate' => $dispos['tripsByDate'],  // Trajets par date
        ]);
    }
    
}
