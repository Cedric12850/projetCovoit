<?php

namespace App\Controller;

use App\Repository\TripRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $usrRep,
                          TripRepository $tripRep,): Response
    {

        $AnAvant= 8 ;
        $UserAvant = 45470;
        $TripAvant = 3540;
        $ResaAvant = 7860;

        $nbUsers = $usrRep->count();
        $nbActiveUsers =  $usrRep->count(['active' =>True]);

        $nbCreesTrips = $tripRep->count(['cancel' =>false]);

        // Date du jour à 00:00:01
        $dateRef = new DateTime();
        $dateRef->add(new DateInterval('P1D'));     // P1D = Period of 1 Day + 1 => 
        $dateRef->setTime(0, 0, 1);                 //  Définir l'heure à 00:00:01

        $nbDispoTrips = $tripRep->createQueryBuilder('t')
                                ->select('COUNT(t.id)')
                                ->where('t.cancel = :cancel')
                                ->andWhere('t.date_start > :today')
                                ->setParameter('cancel', false)
                                ->setParameter('today', $dateRef)
                                ->getQuery()
                                ->getSingleScalarResult();

        


        // $sql = "(SELECT R.user_Id, S.trip_id, MAX(R.nb_place)
        //         FROM reservation R
        //         INNER JOIN step S ON R.step_id = S.id
        //         INNER JOIN trip T ON S.trip_id  = T.Id 
        //         GROUP BY R.user_Id, S.trip_id) as listReser";




        return $this->render('home/index.html.twig', [
            'NbAnnees' => $AnAvant,
            'nbUsers' => $UserAvant + $nbUsers,
            'nbActiveUsers' => $UserAvant + $nbActiveUsers,

            'nbCreesTrips' => $TripAvant + $nbCreesTrips,
            'nbDispoTrips' => $TripAvant + $nbDispoTrips,

            'nbCreesResa' => $ResaAvant + $nbCreesTrips,
            'nbResaCours' => $ResaAvant + $nbDispoTrips

        ]);
    }
}
