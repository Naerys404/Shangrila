<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    #[Route('/admin/orders', name: 'admin_orders_list')]
    public function index(): Response
    {
        return $this->render('admin/order/index.html.twig', [
            'title' => 'Commandes et livraisons',
        ]);
    }
}
