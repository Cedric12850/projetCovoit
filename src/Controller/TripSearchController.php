<?php

namespace App\Controller;

use App\Entity\Town;
use App\Entity\Trip;
use App\Form\TripSearchType;
use App\Repository\StepRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TripSearchController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/tripsearch', name: 'app_trip_search')]
    public function index(Request $request,
    TripRepository $tripRepository,
    EntityManagerInterface $emi
    ): Response
    {
        // Générer l'URL pour l'autocomplétion
        $autocompleteUrl = $this->generateUrl('api_towns');

        $form = $this->createForm(TripSearchType::class, null, [
        ]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
            /* dd($data); */
            $townStart = $data['town_start'];
            $townStartId = $data['town_start_id'];
            /* dump($townStartId); */
            
            $townEnd = $data['town_end'];
            $townEndId = $data['town_end_id'];
            /* dd($townEndId); */

            $dateStart = $data['date_start'];
            $nbPassenger = $data['nb_passenger'];

/*             $repository = $this->entityManager->getRepository(Trip::class);
            $results = $repository->createQueryBuilder('trip'); */

/*                 ->join('trip.steps', 'step')
                ->where('trip.town_start = :townStart')
                ->andWhere('step.town_step = :townEnd')
                ->andWhere('trip.date_start = :dateStart')
                ->andWhere('trip.nb_passenger >= :nbPassenger')
                ->setParameter('townStart', $townStartId)
                ->setParameter('townEnd', $townEndId)
                ->setParameter('dateStart', $dateStart)
                ->setParameter('nbPassenger', $nbPassenger)
                ->getQuery()
                ->getResult(); */
            
            $results = $tripRepository->findDispoTrajet($townStartId, $townEndId, '2024-12-26', "", $nbPassenger, true, $emi);
            dump($results);
            return $this->render('tripsearch/tripsearchresult.html.twig', [
                'town_start' => $townStart,
                'town_end' => $townEnd,
                'date_start' => $dateStart,
                'nb_passenger' => $nbPassenger,
                'resultats' => $results,
                
            ]);
        }


        return $this->render('tripsearch/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/tripsearch/tripresultshow/{id}', name: 'app_tripresult_show')]
    public function tripResultShow(int $id, EntityManagerInterface $emi): Response    
    {
        $tripShow = $this->entityManager->getRepository(Trip::class)->find($id);
        /* dd($tripShow); */
        if (!$tripShow) {
            throw $this->createNotFoundException('Le trajet n\'a pas été trouvé. Peut-être a-t-il annulé?');
        }

        // Récupérer toutes les étapes du trajet
        $steps = $tripShow->getSteps();
        /* dd($steps); */

        $townEnd = null;
        if ($steps->count() > 0) {
            $lastStep = $steps->last(); // Récupère la dernière étape
            $townEnd = $lastStep->getTownStep(); // Récupère la propriété place_step de la dernière étape
        }
        $comfortable = 0;
        if ($comfort = 0){
            $comfortable ='Non';
        }
        else{
        $comfortable ='Oui';
        }
        /* dd($townEnd); */

        $sql = "SELECT GROUP_CONCAT(T.name ORDER BY S.num_order SEPARATOR ', ') AS 'etapes', count(*) AS nb_etapes
                FROM step S
                JOIN town T ON S.town_step_id = T.id
                WHERE S.trip_id = $id";
        $stmt = $emi->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $steps = $result->fetchAssociative();
         /* dd($steps); */ 



        return $this->render('tripsearch/tripshow.html.twig', [
            'tripshow' => $tripShow,
            'townEnd' => $townEnd, // Passe la ville d'arrivée à la vue
            'comfortable' => $comfortable, 
            'steps' => $steps
        ]);

    }


    #[Route('/api/towns', name: 'api_towns')]
    public function getTowns(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('q');
    
        $towns = $this->entityManager->getRepository(Town::class)
            ->createQueryBuilder('t')
            ->where('t.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();

            if (empty($towns)) {
                return new JsonResponse(['error' => 'Aucune ville trouvée.'], 404);
            }

    
        $result = [];
        foreach ($towns as $town) {
            $result[] = [
                'id' => $town->getId(),
                'name' => $town->getName(),
            ];
        }
    
        return new JsonResponse($result);
    }


}