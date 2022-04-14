<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Form\AccountType;
use App\Form\CommentType;
use App\Form\RegisterType;
use App\Entity\TableBooking;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TableBookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


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
            $hash = $hasher->hashPassword($user, $user->getHash());

            $user->setHash($hash);

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
    public function myAccount(TableBookingRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        //récupération des résa de tables de l'user par date  
        $tableBookings = $repo->findBy(['booker'=>'user_id'], ['date'=>'DESC'],6, null );
            
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                

                $comment->setRating($comment->getRating())
                ->setContent($comment->getContent())
                ->setAuthor($this->getUser());

                $manager->persist($comment);
                $manager->flush();

                $this->addFlash("success", "Votre commentaire a bien été enregistré.");
            
        }

        

        return $this->render('account/profile.html.twig', [
            'title' => 'Restaurant Shangrila | Mon compte', 'user' => $user, 'tableBookings'=>$tableBookings, 'comment'=>$comment, 'form'=>$form->createView()
        ]);
    }

    //UPDATE DES DONNEES DE PROFIL DE L'USER
    #[Route('/profile/account-update', name:'account_update')]
    #[IsGranted("ROLE_USER")]
    public function updateAccount(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Vos informations ont bien été modifiées.");

            return $this->redirectToRoute('account_home');
        }

        return $this->render("account/account-update.html.twig", ['title'=> 'Restaurant Shangrila | Modification des informations personnelles', 'user'=> $user, 'form'=>$form->createView()]);
       
    }
    //UPDATE DU MDP DE L'USER
    #[Route('/profile/password-update', name:'password_update')]
    #[isGranted("ROLE_USER")]
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher):Response{

        $user = $this->getUser();
        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //le mot de passe actuel n'est pas bon
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){

                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez entré n'est pas votre mot de passe actuel."));
            } else {
                //récupération du nouveau mdp
                $newPassword = $passwordUpdate->getNewPassword();

                //hash du nouveau mdp
                $hash = $hasher->hashPassword($user, $newPassword);

                //on set le nouveau mdp
                $user->setHash($hash);

                //ok donc on envoie à la bdd
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Votre nouveau mot de passe a bien été enregistré.");

            }

        }

        return $this->render("account/password-update.html.twig", ["title"=>"Restaurant Shangrila | Modification de votre mot de passe", 'form'=>$form->createView()]);

    }


    
}
