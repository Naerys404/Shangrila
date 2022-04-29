<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\AdminBlogType;
use App\Repository\BlogRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBlogController extends AbstractController
{
    //liste des articles de blog
    #[Route('/admin/blog/{page<\d+>?1}', name: 'admin_blog_list')]
    public function index(Pagination $paginationService, $page, Request $request, EntityManagerInterface $manager): Response
    {
        $blog = new Blog();
        $form = $this->createForm(AdminBlogType::class, $blog);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($blog);
            $manager->flush();

            $this->addFlash('success', "L'article a bien été ajouté.");
        }

        $paginationService->setEntityClass(Blog::class)
                           ->setLimit(8)
                           ->setPage($page)
                           ;

        return $this->render('admin/blog/index.html.twig', [
            'title'=>'Gestion du blog et des articles', "pagination" => $paginationService, 'form'=>$form->createView()
        ]);
    }

    //modification des articles
   #[Route('/admin/blog/{id}/edit', name:'admin_blog_edit')] 
   public function edit(Blog $blog, Request $request, EntityManagerInterface $manager):Response{

       $form = $this->createForm(AdminBlogType::class, $blog);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

           $manager->persist($blog);
           $manager->flush();

           $this->addFlash('success', "L'article a bien été modifié.");
           return $this->redirectToRoute('admin_blog_list');
       }
       return $this->render('/admin/blog/edit.html.twig', ['blog'=>$blog, 'form'=>$form->createView(), 'title'=>'Modification d\'un article']
   );}
   //suppression d'un article
   #[Route('/admin/blog/{id}/delete', name:'admin_blog_delete')]
   public function deleteMeal(Blog $blog, EntityManagerInterface $manager):Response{
     
           $manager->remove($blog);
           $manager->flush();
           
           $this->addFlash('success', "L'article' a bien été supprimé.");
       
            return $this->redirectToRoute("admin_blog_list");
    }
}
