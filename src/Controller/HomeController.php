<?php

namespace App\Controller;

use App\Entity\TableBooking;
use App\Form\TableBookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    //homePage + intégration de la réservation d'une table via la homepage
    #[Route('/', name: 'homePage')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $tableBooking = new TableBooking();

        $form = $this->createForm(TableBookingType::class, $tableBooking);

        //si l'user est connecté
        if($user){
            
            $form->handleRequest($request);
            
            //si le formulaire est bien envoyé, on flush les données et on redirige vers la page de confirmation
            if($form->isSubmitted() && $form->isValid()){

                $user = $this->getUser();
                $tableBooking->setBooker($user);

                $manager->persist($tableBooking);
                $manager->flush();

               return $this->redirectToRoute('table_booking_confirmation', ['id'=>$tableBooking->getId()]);
            }

        }
        
        return $this->render('home/home.html.twig', [
            'title' => 'Restaurant Shangrila | Accueil', 'form'=>$form->createView()
        ]);
    }

    //page "A propos" du restaurant
    #[Route('/about', name:'aboutPage')]
    public function about(){
        
        return $this->render('home/about.html.twig', ['title' => 'Restaurant Shangrila | A propos']);
    }
}
