<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripSearchType;
use App\Repository\StepRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(
        Request $request,
/*         TripRepository $tripRepository,
        StepRepository $stepRepository */
    ): Response
    {
        $form = $this->createForm(type: TripSearchType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
            /* dd($data); */
            $townStart = $data['town_start'];
            $townEnd = $data['town_end'];
            $dateStart = $data['date_start'];
            /* $dateStart = $data['date_start']; */
            $nbPassenger = $data['nb_passenger'];

            $repository = $this->entityManager->getRepository(Trip::class);
            $results = $repository->createQueryBuilder('trip')
                /* ->join('trip.town_start_id', 'townStart') */
                /* ->select('trip.*')  */
                ->join('trip.steps', 'step')
                ->where('trip.town_start = :townStart')
                ->andWhere('step.town_step = :townEnd')
                ->andWhere('trip.date_start = :dateStart')
                ->andWhere('trip.nb_passenger >= :nbPassenger')
                ->setParameter('townStart', $townStart)
                ->setParameter('townEnd', $townEnd)
                ->setParameter('dateStart', $dateStart)
                ->setParameter('nbPassenger', $nbPassenger)
                ->getQuery()
                ->getResult();
                /* dd($results); */
            return $this->render('tripsearch/tripsearchresult.html.twig', [
                'results' => $results,
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
    public function tripResultShow(int $id): Response    
    {
        $tripShow = $this->entityManager->getRepository(Trip::class)->find($id);
        /* dd($tripShow); */
        if (!$tripShow) {
            throw $this->createNotFoundException('Le trajet n\'a pas été trouvé. Peut-être a-t-il annulé?');
        }

        // Récupérer toutes les étapes du trajet
        $steps = $tripShow->getSteps();
        /* dd($steps); */
        // Suppose que la dernière étape contient l'information que tu cherches
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

        return $this->render('tripsearch/tripshow.html.twig', [
            'tripshow' => $tripShow,
            'townEnd' => $townEnd, // Passe la ville d'arrivée à la vue
            'comfortable' => $comfortable
        ]);

    }
}