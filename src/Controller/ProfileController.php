<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile(
        UserRepository $userRepository,
        int $id
        ): Response
    {
        $user = $userRepository->find($id);
        dump($user);
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
