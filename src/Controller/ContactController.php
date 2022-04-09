<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contactData = $form->getData();

            $message = (new Email())
                    ->from($contactData['email'])
                    ->to('naerys@test.com')
                    ->subject('Restaurant Shangrila : Mail reçu via le formulaire de contact')
                    ->text('Expediteur: '.$contactData['email'].\PHP_EOL.
                    $contactData['message'],
                    'text/plain');
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('contact');
        }



        return $this->render('contact/index.html.twig', [
            'title'=>'Restaurant Shangrila | Contact', 'form'=>$form->createView()
        ]);
    }
}
