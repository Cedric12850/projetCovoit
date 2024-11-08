<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail')]
    public function sendMail(
        MailerInterface $mailer
    ): Response
    {
        $mail = (new Email())
            ->from(new Address('mailtrap@example.com', 'MailTrap'))
            ->to('newuser@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject("test d'envoi de mail")
            ->text('mail envoyé par friendly trip')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($mail);
            
        return new Response('Email envoyé avec succès!');
        // return $this->render('mail/index.html.twig', [
        //     'controller_name' => 'MailController',
        // ]);
    }
}
