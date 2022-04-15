<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminContactController extends AbstractController
{
    #[Route('/admin/contacts', name: 'admin_contacts_list')]
    public function index(): Response
    {
        return $this->render('admin/contact/index.html.twig', [
            'title' => 'Messagerie',
        ]);
    }
}
