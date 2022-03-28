<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddressType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    //CONNEXION
    #[Route('/login', name:'account_login')]
    public function login(AuthenticationUtils $authenticationUtils):Response{

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('account/login.html.twig', [
            'title' => 'Restaurant Shangrila | Connexion', 'last_username'=>$lastUsername, 'error'=> $error
        ]);

    }

    //DECONNEXION
    #[Route('/logout', name:'account_logout')]
    public function logout(){

    }

    //INSCRIPTION
    #[Route('/register', name:'account_register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager){
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

     
        if($form->isSubmitted() && $form->isValid()){

            //hash du mdp
            $hash = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a bien été créé !");

            return $this->redirectToRoute('account_login');

        }

        return $this->render('account/register.html.twig', ['title'=>'Restaurant Shangrila | Inscription', 'form'=>$form->createView()]);
    }

    //PROFIL DE L'USER (connecté uniquement)
    #[Route('/profile', name: 'account_home')]
    #[IsGranted("ROLE_USER")]
    public function myAccount(): Response
    {

        return $this->render('account/profile.html.twig', [
            'title' => 'Restaurant Shangrila | Mon compte', 'user' => $this->getUser()
        ]);
    }
}
