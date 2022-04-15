<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTableBookingController extends AbstractController
{
    #[Route('/admin/table/bookings', name: 'admin_tablebookings_list')]
    public function index(): Response
    {
        return $this->render('admin/tableBooking/index.html.twig', [
            'title' => 'Réservations des clients',
        ]);
    }
}
