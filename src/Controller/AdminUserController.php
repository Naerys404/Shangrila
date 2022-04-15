<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users_list')]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'title' => 'Gestion des clients',
        ]);
    }
}
