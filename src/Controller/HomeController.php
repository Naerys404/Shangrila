<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\TableBooking;
use App\Form\TableBookingType;
use App\Repository\MealRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    //homePage + intégration de la réservation d'une table via la homepage
    #[Route('/', name: 'homePage')]
    public function index(Request $request, EntityManagerInterface $manager, MenuRepository $menuRepo, MealRepository $mealRepo): Response
    {
        //recuperations des != menus 
        $menus = $menuRepo->findAll();
        $meals = $mealRepo->findAll();

        $user = $this->getUser();
        $tableBooking = new TableBooking();

        $form = $this->createForm(TableBookingType::class, $tableBooking);

        //si l'user est connecté
        if($user){
            
            $form->handleRequest($request);
            
            //si le formulaire de résa est bien envoyé, on flush les données et on redirige vers la page de confirmation
            if($form->isSubmitted() && $form->isValid()){

                $user = $this->getUser();
                $tableBooking->setBooker($user);

                $manager->persist($tableBooking);
                $manager->flush();

               return $this->redirectToRoute('table_booking_confirmation', ['id'=>$tableBooking->getId()]);
            }

        }
        
        return $this->render('home/home.html.twig', [
            'title' => 'Restaurant Shangrila | Accueil', 'form'=>$form->createView(), 'menu'=>$menus, 'meal'=>$meals
        ]);
    }

    //page "A propos" du restaurant
    #[Route('/about', name:'about')]
    public function about(){
        
        return $this->render('home/about.html.twig', ['title' => 'Restaurant Shangrila | A propos']);
    }

    //page galerie du restaurant
    #[Route('/gallery', name:'gallery')]
    public function gallery(MenuRepository $menuRepo, MealRepository $mealRepo){

        $menu = $menuRepo->findAll();
        $meal = $mealRepo->findAll();

        
        return $this->render('home/gallery.html.twig', ['title'=>'Restaurant Shangrila | Galerie', 'menu'=>$menu, 'meal'=>$meal]);
    }
}
