<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsageConditionController extends AbstractController
{
    #[Route('/usage/condition', name: 'app_usage_condition')]
    public function index(): Response
    {
        return $this->render('usage_condition/index.html.twig', [
            'controller_name' => 'UsageConditionController',
        ]);
    }
}
