<?php

namespace App\Controller;

use App\Service\Stats;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    //dashboard général avec stats (compte du nb de clients, réservations, commentaires ...) et top commentaires / pire commentaires
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(Stats $statsService): Response
    {
        $stats = $statsService->getStats();

        $bestComs = $statsService->getCommentStats('DESC');
        $worstComs = $statsService->getCommentStats('ASC');


        return $this->render('admin/dashboard/index.html.twig', [
            'title' => 'Tableau de bord', 'stats'=>$stats, 'bestComs'=>$bestComs, 'worstComs'=>$worstComs
        ]);
    }
}
