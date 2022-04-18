<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Menu;
use App\Form\AdminMealType;
use App\Form\AdminMenuType;
use App\Service\Pagination;
use App\Form\AdminAddMealType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMenuController extends AbstractController
{
    //liste des menus et des plats pour gestion admin
    #[Route('/admin/menus/{page<\d+>?1}', name: 'admin_menus_list')]
    public function index(Pagination $paginationService, $page, Request $request, MenuRepository $repo, EntityManagerInterface $manager): Response
    {
        $meal = new Meal();

        $form = $this->createForm(AdminAddMealType::class, $meal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($meal);
            $manager->flush();

            $this->addFlash('success', "Le plat a bien été ajouté.");
        }

        $menus = $repo->findAll();
        $paginationService->setEntityClass(Meal::class)
                           ->setLimit(8)
                           ->setPage($page)
                           ;
        return $this->render('admin/menu/dashboardMenu.html.twig', [
            'title' => 'Gestions des menus et plats', "pagination" => $paginationService, "menus"=>$menus, 'form'=>$form->createView()]);
    }

    //modification des menus
   #[Route('/admin/menu/{id}/edit', name:'admin_menu_edit')] 
   public function editMenu(Menu $menu, Request $request, EntityManagerInterface $manager):Response{

       $form = $this->createForm(AdminMenuType::class, $menu);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

           $manager->persist($menu);
           $manager->flush();

           $this->addFlash('success', "Le menu a bien été modifié.");
           return $this->redirectToRoute('admin_menus_list');
       }
       return $this->render('/admin/menu/MenuEdit.html.twig', ['menu'=>$menu, 'form'=>$form->createView(), 'title'=>'Modification d\'un menu']
   );

   }
    //modification des plats
    #[Route('/admin/meals/{id}/edit', name:'admin_meal_edit')] 
    public function editMeal(Meal $meal, Request $request, EntityManagerInterface $manager):Response{

        $form = $this->createForm(AdminMealType::class, $meal);
        $form->handleRequest($request);
 
        if($form->isSubmitted() && $form->isValid()){
 
            $manager->persist($meal);
            $manager->flush();
 
            $this->addFlash('success', "Le plat a bien été modifié.");
            return $this->redirectToRoute('admin_menus_list');
        }
        return $this->render('/admin/menu/mealEdit.html.twig', ['meal'=>$meal, 'form'=>$form->createView(), 'title'=>'Modification d\'un plat']
    );
 
    }

   //suppression d'un menu
   #[Route('/admin/menu/{id}/delete', name:'admin_menu_delete')]
   public function deleteMenu(Menu $menu, EntityManagerInterface $manager):Response{
       if(count($menu->getMeals()) > 0) {
           $this->addFlash("warning", "Vous ne pouvez pas supprimer un menu sans supprimer les plats qui le composent.");
       } else {

           $manager->remove($menu);
           $manager->flush();
           
           $this->addFlash('success', "Le menu a bien été supprimé.");
       }
            return $this->redirectToRoute("admin_menus_list");
    }

    //suppression d'un plat
   #[Route('/admin/meal/{id}/delete', name:'admin_meal_delete')]
   public function deleteMeal(Meal $meal, EntityManagerInterface $manager):Response{
     
           $manager->remove($meal);
           $manager->flush();
           
           $this->addFlash('success', "Le plat a bien été supprimé.");
       
            return $this->redirectToRoute("admin_menus_list");
    }
}
