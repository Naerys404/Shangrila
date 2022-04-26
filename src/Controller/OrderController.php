<?php

namespace App\Controller;

use Stripe\Charge;
use Stripe\Stripe;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Order;
use App\Manager\StripeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('order/', name:'order')]
    public function index(){
        return $this->redirectToRoute('homeMenu');
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/order/payment/{id}', name: 'payment')]
    public function payment(Menu $menu, StripeManager $stripeManager):Response {

                        
            return $this->render('order/payment.html.twig', ['title'=>'Restaurant Shangrila | Récapitulatif et paiement de votre commande',
              'menu'=>$menu, 
              'user'=>$this->getUser(), 'intentSecret'=>$stripeManager->intentSecret($menu),'id'=>$menu->getId()]);
    }
    
 
    #[IsGranted("ROLE_USER")]
    #[Route('order/payment/{id}/load', name:'subscription_payment', methods:['GET', 'POST'])]
    public function subscription(Menu $menu, Request $request, StripeManager $stripeManager){

        $user = $this->getUser();
        $id = $menu->getId();
        if($request->getMethod() === 'POST'){

            $resource = $stripeManager->stripe($_POST, $menu);

            //si tout s'est bien passé 
            if($resource !== null){
                $stripeManager->create_subscription($resource, $menu, $user);
                return $this->redirectToRoute('success_payment', array('id' => $id));
            }

        }

        return $this->redirectToRoute('payment', ['id'=>$menu->getId()]);
    }

    #[Route('/order/payment/{id}/success', name:"success_payment")]
    public function success(Menu $menu){

        return $this->render('order/success.html.twig',['title'=>'Restaurant Shangrila | Commande validée', 'menu'=>$menu, 'id'=>$menu->getId()]);
    }

}