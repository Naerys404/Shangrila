<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMenuController extends AbstractController
{
    #[Route('/admin/menus', name: 'admin_menus_list')]
    public function index(): Response
    {
        return $this->render('admin/menu/dashboardMenu.html.twig', [
            'title' => 'Gestions des menus et plats',
        ]);
    }
}
