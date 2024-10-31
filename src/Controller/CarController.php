<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\SpecificityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{

    
    #[Route('/car', name: 'app_car')]
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }

    # Route for add a car
    #[Route('/car/new', name: 'app_car_new')]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager
        ): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $specar = $form->get('specificities')->getData();
            foreach ($specar as $specificity) {
                $car->addSpecificity($specificity);
            }
            
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #route for edit a car
    #[Route('/car/edit/{id}', name: 'app_car_edit')]
    public function edtitCar(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        CarRepository $carRepository,
        int $id,
    ): Response
    {
        $car = $entityManagerInterface->getRepository(car::class)->find($id);
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this-> render('car/edit.html.twig', [
            'editform' => $form,
            'car' => $car,
        ]);

    }
}
