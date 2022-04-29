<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Pagination;
use App\Form\AdminOrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminOrderController extends AbstractController
{
    //liste des commandes en livraison
    #[Route('/admin/orders/{page<\d+>?1}', name: 'admin_orders_list')]
    public function index(Pagination $paginationService, $page): Response
    {
        $paginationService->setEntityClass(Order::class)
        ->setLimit(10)
        ->setPage($page)
        ;


        return $this->render('admin/order/index.html.twig', [
            'title' => 'Commandes et livraisons', "pagination" => $paginationService
        ]);
    }

    //administration des commandes
   #[Route('/admin/order/{id}/edit', name:'admin_order_edit')] 
   public function edit(Order $order, Request $request, EntityManagerInterface $manager):Response{

       $form = $this->createForm(AdminOrderType::class, $order);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

           $manager->persist($order);
           $manager->flush();

           $this->addFlash('success', "La commande a bien été modifiée.");
           return $this->redirectToRoute('admin_orders_list');
       }
       return $this->render('/admin/order/edit.html.twig', ['order'=>$order, 'form'=>$form->createView(), 'title'=>'Gestion d\'une commande']
   );

   }

   //validation de la commande livrée 
   #[Route('admin/order/{id}/delivered', name:'admin_order_delivered')]
   public function delivered(Order $order, EntityManagerInterface $manager):Response{

        //$order = $manager->createQuery('SET ??? '); 
        $manager->persist($order);
        $manager->flush();

        return $this->render('admin/comment/index.html.twig', [
        'title' => 'Gestion des commandes et livraisons',
    ]);
   }


    //suppression d'une commande
    #[Route('/admin/order/{id}/delete', name:'admin_order_delete')]
    public function delete(Order $order, EntityManagerInterface $manager):Response{
       
            $manager->remove($order);
            $manager->flush();
            
            $this->addFlash('success', "La commande a bien été supprimée.");
            return $this->redirectToRoute('admin_orders_list');
 
        
     }



}
