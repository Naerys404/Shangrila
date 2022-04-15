<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MealRepository;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'homeMenu')]
    public function index(MenuRepository $menuRepo): Response
    {
        $menus = $menuRepo->findAll();

        return $this->render('menu/homeMenu.html.twig', [
            'title'=>'Restaurant Shangrila | Menu', 'menu'=>$menus
        ]);
    }

    #[Route('/menu/show/{id}', name:'showMenu')]
    public function show(Menu $menu){

        return $this->render('menu/showMenu.html.twig', ['title'=>'Restaurant Shangrila | Détail du menu', 'menu'=>$menu, 'id'=>$menu->getId()]);
    }


}
