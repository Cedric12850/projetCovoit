<?php

namespace App\Controller;

use App\Entity\Town;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TownController extends AbstractController
{
    #[Route('/town', name: 'app_town')]
    public function index(): Response
    {
        return $this->render('town/index.html.twig', [
            'controller_name' => 'TownController',
        ]);
    }

}
