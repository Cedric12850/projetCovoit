<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\TownRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    // #test route pour l'autocomplétion
    // #[Route('/car/index', name: 'app_car')]
    //     public function townAutocomplete(Request $request, TownRepository $townRepository): JsonResponse
    //     {
    //         // Requête pour obtenir les villes correspondantes
    //         $towns = $townRepository->findByQuery('15%'); // méthode `findByQuery` dans le repository

    //         // Retourner les noms des villes au format JSON
    //         $townNames = array_map(fn($town) => $town->getName(), $towns);
    //         dd($townNames);
    //         return $this->redirectToRoute('app_car');
    //     }


    // Route pour la requête Ajax qui génère les ville par le zipcode
    #[Route('/search-towns', name: 'search_towns')]
    public function searchTowns(Request $request, TownRepository $townRepository): JsonResponse
    {
        $zipCode = $request->query->get('zip_code');
        if (!$zipCode) {
            return new JsonResponse(['error' => 'No zip code provided'], 400);
        }

        $zipCode .= '%';
        $towns = $townRepository->findByQuery($zipCode);
        $townNames = array_map(fn($town) => ['id' => $town->getId(), 'name' => $town->getName(), 'zip_code' => $town->getZipCode()], $towns);

        return new JsonResponse($townNames);
    }

    #[Route('/register', name: 'app_register')]
    public function register(
    Request $request, 
    UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $entityManager, 
    SluggerInterface $slugify,
    #[Autowire('%kernel.project_dir%/assets/uploads')] string $uploadDirectory,
    TownRepository $townRepository
    ): Response
    {

        
        // Redirection si l'utilisateur est déjà authentifié
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            // Récupérer et définir la ville
        $townId = $form->get('town')->getData();
        if ($townId) {
            $town = $townRepository->find($townId);
            if ($town) {
                $user->setTown($town);
            }
        }
            /** @var UploadedFile $brochureFile */
            $image = $form->get('photo')->getData();

            if ($image) {

                $originImageName = pathinfo( $image->getClientOriginalName(), PATHINFO_FILENAME );
                $imageNameSlugered = $slugify->slug($originImageName);
                $newImageName = $imageNameSlugered.'-'.uniqid().'.'.$image->guessExtension();

                try
                {
                    $image->move( $uploadDirectory, $newImageName );
                }
                catch (FileException $e) 
                {
                    $this->addFlash('error', $e->getMessage());
                }
                $user->setPhoto($newImageName);
            }
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
    
            // Encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
    
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();
            
            // Send confirmation email
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@projetcovoit.com', 'Team covoit'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
    
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            
        ]);
    }
    

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse courriel a été vérifié.');

        return $this->redirectToRoute('app_register');
    }
}
