<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Service\Pagination;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    //liste d'article avec pagination
    #[Route('/blog/{page<\d+>?1}', name: 'homeBlog')]
    public function index(Pagination $paginationService, $page): Response
    {
        $paginationService->setEntityClass(Blog::class)
                            ->setPage($page)
                            ->setLimit(10)
        ;

        return $this->render('blog/index.html.twig', [
            'title'=>'Restaurant Shangrila | Blog', 'pagination'=>$paginationService
        ]);
    }

    //detail de l'article
    #[Route('blog/show/{id}', name:'blog_show')]
    public function show(Blog $blog):Response{

        return $this->render('blog/show.html.twig', [
            'title'=>'Restaurant Shangrila | Détail de l\'article', 'blog'=>$blog
        ]);
    }
}
