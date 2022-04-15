<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    #[Route('/admin/comments', name: 'admin_comments_list')]
    public function index(): Response
    {
        return $this->render('admin/comment/index.html.twig', [
            'title' => 'Gestion des commentaires',
        ]);
    }
}
