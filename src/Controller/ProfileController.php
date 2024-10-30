<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CarRepository;
use App\Repository\TownRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile(
        UserRepository $userRepository,
        CarRepository $carRepository,
        int $id
        ): Response
    {
        $user = $userRepository->find($id);
        $carUser = $carRepository->find($user);
        dump($carUser);
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'carUser' => $carUser,
        ]);
    }



    // Route fo update profile
    #[Route('/profile/edit/{id}', name: 'app_profile_edit')]
    public function editProfile(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/assets/uploads')] string $uploadDirectory,
        int $id,
        TownRepository $townRepository
    ):Response
    {
        $user =$entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
             // Récupérer et définir la ville
        $townId = $form->get('town')->getData();
        if ($townId) {
            $town = $townRepository->find($townId);
            if ($town) {
                $user->setTown($town);
            }
        }
            $thumbnail = $form->get('photo')->getData();
            if ($thumbnail) {
                //Récuperation du nom d'origine de l'image
                $originalFileName = pathinfo($thumbnail->getClientOriginalName(),PATHINFO_FILENAME);
                // slugger pour nettoyer des espaces et caractères spéciaux
                $safeFileName = $slugger->slug($originalFileName);
                //attribution d'un id unique et guessExtension ajoutte le jpg ou png ...
                $newFileName = $safeFileName.'-'.uniqid().'.'.$thumbnail->guessExtension();
                 // Move the file to the directory where brochures are stored
                 try {
                    $thumbnail->move($uploadDirectory, $newFileName);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'uploadFilename' property to store the PDF file name
                // instead of its contents
                $user->setPhoto($newFileName);
            }

            $plainPassword = $form->get('plainPassword')->getData();
            // Encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            
            $user = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return 
    $this->render('profile/edit.html.twig', [
        'editform' => $form,
        'user' => $user
    ]);

    }
}
