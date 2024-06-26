<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    //connexion au panneau admin
    #[Route('/admin/login', name: 'admin_account_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', 
        [ 'hasError'=>$error!==null,
         'username'=>$username ]);
    }
    //deconnexion de la partie admin
    #[Route('admin_logout', name:'admin_account_logout')]
    public function logout(){

    }

}
