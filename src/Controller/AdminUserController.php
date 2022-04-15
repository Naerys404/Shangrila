<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
   //liste des clients
   #[Route('/admin/users/{page<\d+>?1}', name: 'admin_users_list')]
   public function index(Pagination $paginationService, $page): Response
   {
       $paginationService->setEntityClass(User::class)
                           ->setLimit(5)
                           ->setPage($page)
                           ;

       return $this->render('admin/user/index.html.twig', [
           'title' => 'Gestion des clients', "pagination" => $paginationService
       ]);
   }

   //modération des descriptions des users
   #[Route('/admin/user/{id}/edit', name:'admin_user_edit')] 
   public function edit(User $user, Request $request, EntityManagerInterface $manager):Response{
       $form = $this->createForm(AdminUserType::class, $user);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

           $manager->persist($user);
           $manager->flush();

           $this->addFlash('success', "Le profil de l'utilisateur a bien été modéré.");
           return $this->redirectToRoute('admin_users_list');
       }
       return $this->render('/admin/user/edit.html.twig', ['user'=>$user, 'form'=>$form->createView(), 'title'=>'Modération d\'un profil d\'utilisateur']
   );

   }

   //suppression des clients
   #[Route('/admin/user/{id}/delete', name:'admin_user_delete')]
   public function delete(User $user, EntityManagerInterface $manager):Response{
       if(count($user->getTableBookings()) > 0 or count($user->getOrders()) > 0 ) {
           $this->addFlash("warning", "Vous ne pouvez pas supprimer un utilisateur qui possède des commandes ou des réservations.");
       } else {

           $manager->remove($user);
           $manager->flush();
           
           $this->addFlash('success', "L'utilisateur a bien été supprimé.");
       }
            return $this->redirectToRoute("admin_users_list");
    }
}
