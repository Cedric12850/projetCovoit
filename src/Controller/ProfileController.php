<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CarRepository;
use App\Repository\TownRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException as ExceptionAccessDeniedException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile(
        UserRepository $userRepository,
        TripRepository $tripRepository,
        int $id
        ): Response
    {
        $user = $userRepository->find($id);
        $tripUser = $tripRepository->findAllTripsByUserId($id);
        $carUser = $user->getCars();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'tripUser'=> $tripUser,
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
        $form = $this->createForm(RegistrationFormType::class, $user, ['is_edit' => true]);   //change la valeur is_edit pour que le formulaire n'oblige pas le changement de mdp
        $form->handleRequest($request);
            //Récupération des véhicule pour les afficher dans la pages edit
        $carUser = $user->getCars();

        // Condition d'accès à l'édition de profil
        if($this->getUser() !== $user){
            throw new ExceptionAccessDeniedException('Vous ne disposez pas des droits pour réaliser cette action.');
        } else {

            if ($form->isSubmitted()&& $form->isValid())
            {
                
                // Récupérer et définir la ville et le zipcode
            $townId = $form->get('town')->getData();
            $zip_code = $form->get('zip_code')->getData();
            if ($townId) {
                $town = $townRepository->find($townId);
                $zip_code = $townRepository->find($zip_code);
                if ($town) {
                    $user->setTown($town);
                    $user->setZipCode($town->getZipCode());
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

                    // Préservation du mdp si pas de changement
                $plainPassword = $form->get('plainPassword')->getData();
                    if ($plainPassword) {
                    $plainPassword = $form->get('plainPassword')->getData();
                    // Encode the plain password
                    $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
                }
                $user = $form->getData();
                $entityManager->flush();

                return $this->redirectToRoute('app_profile',['id' => $id]);
            }
        }
        

        return 
    $this->render('profile/edit.html.twig', [
        'editform' => $form,
        'user' => $user,
        'carUser' => $carUser,
    ]);

    }
}
