<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\Pagination;
use App\Form\AdminCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    //liste des commentaires
    #[Route('/admin/comments/{page<\d+>?1}', name: 'admin_comments_list')]
    public function index(Pagination $paginationService, $page): Response
    {
    
       $paginationService->setEntityClass(Comment::class)
                           ->setLimit(10)
                           ->setPage($page)
                           ;

       return $this->render('admin/comment/index.html.twig', [
           'title' => 'Gestion des commentaires', "pagination" => $paginationService
       ]);
   }

   //modération des commentaires des clients
   #[Route('/admin/comment/{id}/edit', name:'admin_comment_edit')] 
   public function edit(Comment $comment, Request $request, EntityManagerInterface $manager):Response{

       $form = $this->createForm(AdminCommentType::class, $comment);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

           $manager->persist($comment);
           $manager->flush();

           $this->addFlash('success', "Le commentaire a bien été modéré.");
           return $this->redirectToRoute('admin_comments_list');
       }
       return $this->render('/admin/comment/edit.html.twig', ['comment'=>$comment, 'form'=>$form->createView(), 'title'=>'Modération d\'un commentaire client']
   );

   }

   //suppression des commentaires
   #[Route('/admin/comment/{id}/delete', name:'admin_comment_delete')]
   public function delete(Comment $comment, EntityManagerInterface $manager):Response{
      
           $manager->remove($comment);
           $manager->flush();
           
           $this->addFlash('success', "Le commentaire a bien été supprimé.");
           return $this->redirectToRoute('admin_comments_list');

    
        return $this->render('admin/comment/index.html.twig', [
            'title' => 'Gestion des commentaires',
        ]);
    }
}
