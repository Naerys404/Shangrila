<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Entity\TableBooking;
use App\Form\AdminTableBookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTableBookingController extends AbstractController
{
   //liste les réservations
   #[Route('/admin/table/bookings/{page<\d+>?1}', name: 'admin_tablebookings_list')]
   public function index(Pagination $paginationService, $page): Response
   {
       $paginationService->setEntityClass(TableBooking::class)
                           ->setLimit(10)
                           ->setPage($page)
                           ;

       return $this->render('admin/tableBooking/index.html.twig', [
           'title' => 'Gestion des réservations', "pagination" => $paginationService
       ]);
   }

   //modification d'une réservation
   #[Route('/admin/table/booking/{id}/edit', name:'admin_tablebooking_edit')] 
   public function edit(TableBooking $tableBooking, Request $request, EntityManagerInterface $manager):Response{

       $form = $this->createForm(AdminTableBookingType::class, $tableBooking);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

           $manager->persist($tableBooking);
           $manager->flush();

           $this->addFlash('success', "La réservation a bien été modifiée.");
           return $this->redirectToRoute('admin_tablebookings_list');
       }
       return $this->render('/admin/tableBooking/edit.html.twig', ['tableBooking'=>$tableBooking, 'form'=>$form->createView(), 'title'=>'Modification d\'une réservation']
       );

   }

   //suppression d'une réservation
   #[Route('/admin/table/booking/{id}/delete', name:'admin_tablebooking_delete')]
   public function delete(TableBooking $tableBooking, EntityManagerInterface $manager):Response
   {
           $manager->remove($tableBooking);
           $manager->flush();
           
           $this->addFlash('success', "La réservation a bien été supprimée.");
           return $this->redirectToRoute("admin_tablebookings_list");
    }
    
}
