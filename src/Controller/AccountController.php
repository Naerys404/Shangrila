<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddressType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    #[Route('/login', name:'account_login')]
    public function login(AuthenticationUtils $authenticationUtils):Response{
        //Affiche l'erreur si il y en a une, ou la dernière erreur de la liste
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        

        return $this->render('account/login.html.twig', [
            'title' => 'Restaurant Shangrila | Connexion', 'last_username'=>$lastUsername, 'error'=> $error
        ]);

    }

    #[Route('/logout', name:'account_logout')]
    public function logout(){

    }

    #[Route('/register', name:'account_register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager){
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        $addressForm = $this->createForm(AddressType::class, $user);
        $addressForm->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $addressForm->isSubmitted() && $addressForm->isValid()  ){

            //hash du mdp
            $hash = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a bien été créé !");

            return $this->redirectToRoute('account_login');

        }

        return $this->render('account/register.html.twig', ['title'=>'Restaurant Shangrila | Inscription', 'form'=>$form->createView(), 'addressForm'=>$addressForm->createView()]);
    }

    #[Route('/account', name: 'account_home')]
    public function myAccount(): Response
    {

        return $this->render('account/profile.html.twig', [
            'title' => 'Restaurant Shangrila | Mon compte',
        ]);
    }
}
