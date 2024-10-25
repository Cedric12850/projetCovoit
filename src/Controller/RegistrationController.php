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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
    Request $request, 
    UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $entityManager, 
    SluggerInterface $slugify,
    #[Autowire('%kernel.project_dir%/public/uploads/images')] string $uploadImageDir,
    TownRepository $townRepository
    ): Response
    {

        // Redirection si l'utilisateur est déjà authentifié
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        $user = new User();
        $towns = $townRepository->findAll();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    

            /** @var UploadedFile $brochureFile */
            $image = $form->get('photo')->getData();

            if ($image) {

                $originImageName = pathinfo( $image->getClientOriginalName(), PATHINFO_FILENAME );
                $imageNameSlugered = $slugify->slug($originImageName);
                $newImageName = $imageNameSlugered.'-'.uniqid().'.'.$image->guessExtension();

                try
                {
                    $image->move( $uploadImageDir, $newImageName, $newImageName );
                }
                catch (\Exception $e) 
                {
                    $this->addFlash('error', $e->getMessage());
                }
            
    
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
    
            // Encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
    
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();
            }
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
            'towns' => $towns
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
