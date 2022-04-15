<?php

namespace App\Controller;


use App\Entity\Menu;
use App\Entity\Order;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order/{id}', name: 'order')]
    public function index(Menu $menu): Response
    {
        return $this->render('order/checkout.html.twig', [
            'title'=>'Restaurant Shangrila | Commande du menu', 'menu'=>$menu, 'id'=>$menu->getId()
        ]);
    }

    #[Route('/order/payment', name: 'payment')]
    public function payment(Order $order){
        $order =  new Order();

  
    }
    
    #[Route('/order/success', name:'order_confirmation')]
    public function orderConfirm(){

        return $this->render('order/success.html.twig', ['title'=>'Restaurant Shangrila | Confirmation de votre commande']);
    }
}
