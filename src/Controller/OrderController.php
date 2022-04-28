<?php

namespace App\Controller;

use Stripe\Charge;
use Stripe\Stripe;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Order;
use App\Manager\StripeManager;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('order/', name:'order')]
    public function index(){
        return $this->redirectToRoute('homeMenu');
    }

    //page d'achat du menu
    #[IsGranted("ROLE_USER")]
    #[Route('/order/payment/{id}', name: 'payment')]
    public function payment(Menu $menu, StripeManager $stripeManager):Response {

                        
            return $this->render('order/payment.html.twig', ['title'=>'Restaurant Shangrila | Récapitulatif et paiement de votre commande',
              'menu'=>$menu, 
              'user'=>$this->getUser(), 'intentSecret'=>$stripeManager->intentSecret($menu),'id'=>$menu->getId()]);
    }
    
 
    //paiement
    #[IsGranted("ROLE_USER")]
    #[Route('order/payment/{id}/load', name:'subscription_payment', methods:['GET', 'POST'])]
    public function subscription(Menu $menu, Request $request, StripeManager $stripeManager){

        $user = $this->getUser();
        $id = $menu->getId();
        if($request->getMethod() === 'POST'){

            $resource = $stripeManager->stripe($_POST, $menu);

            //si tout s'est bien passé => creation d'un Order et envoi des données + données stripe
            if($resource !== null){
               $data = $stripeManager->create_subscription($resource, $menu, $user);

                return $this->redirectToRoute('success_payment', array('id' => $id, 'orderReference'=> $data));
            }

        }

        return $this->redirectToRoute('payment', ['id'=>$menu->getId()]);
    }

    //paiement réussi,  page récapitulative ( on limite l'accès de la page récapitulative au propriétaire de la commande)
    #[Route('/order/payment/{id}/success/{orderReference}', name:"success_payment")]
    #[IsGranted('ROLE_USER')]
    public function success(Menu $menu, OrderRepository $repo, $orderReference){

        $order = $repo->findOneBy(array('reference' => $orderReference, 'user'=>$this->getUser()));

        //si $order est null : pas de correlation entre l'user actuel et la référence demandée => on redirige vers l'accueil
        if($order == null ){
            return $this->redirectToRoute('homePage');
        }

        return $this->render('order/success.html.twig',['title'=>'Restaurant Shangrila | Commande validée','order'=>$order, 'menu'=>$menu, 'user'=>$this->getUser()]);
    }

}